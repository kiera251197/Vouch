<?php
class Vouching {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    // Candidate fetching for the Single
    public function getMatchingCandidatesForSingle(int $singleUserId): array {
        $prefStmt = $this->db->prepare("
            SELECT target_gender, target_ages 
            FROM Singles_Preferences 
            WHERE user_id = ?
        ");
        $prefStmt->bind_param("i", $singleUserId);
        $prefStmt->execute();
        $pref = $prefStmt->get_result()->fetch_assoc();
        $prefStmt->close();

        if (!$pref) {
            return [];
        }

        $targetGender = $pref['target_gender'] ?? '';
        $rawAges = $pref['target_ages'] ?? '';
        
        $minAge = 18;
        $maxAge = 99;
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $rawAges, $matches)) {
            $minAge = (int)$matches[1];
            $maxAge = (int)$matches[2];
        } elseif (strpos($rawAges, '56') !== false) {
            $minAge = 56;
            $maxAge = 99;
        }

        $query = "
            SELECT 
                p.user_id,
                p.full_name,
                p.picture_url,
                p.location,
                p.gender,
                p.occupation,
                p.hobbies,
                p.bio,
                p.hook,
                (YEAR(CURDATE()) - CAST(p.birth_year AS UNSIGNED)) AS age,
                GROUP_CONCAT(ph.photo_url ORDER BY ph.photo_id ASC SEPARATOR ',') AS gallery_photos
            FROM Profiles p
            JOIN Users u ON u.user_id = p.user_id
            LEFT JOIN Profile_Photos ph ON ph.user_id = p.user_id
            WHERE p.user_id != ?
            AND u.user_role = 'single'
            AND (
                LOWER(?) LIKE '%all sapphic%'
                OR LOWER(p.gender) = LOWER(?)
                OR (LOWER(?) = 'women' AND LOWER(p.gender) = 'woman')
                OR (LOWER(?) = 'woman' AND LOWER(p.gender) = 'women')
                OR (LOWER(?) LIKE '%trans%' AND LOWER(p.gender) LIKE '%trans%')
                OR (LOWER(?) LIKE '%non-binary%' AND (LOWER(p.gender) LIKE '%non-binary%' OR LOWER(p.gender) LIKE '%enby%'))
            )
            AND (YEAR(CURDATE()) - CAST(p.birth_year AS UNSIGNED)) BETWEEN ? AND ?
            AND p.user_id NOT IN (
                SELECT candidate_user_id 
                FROM Vouching 
                WHERE requesting_single_id = ?
            )
            GROUP BY p.user_id
            ORDER BY RAND()
            LIMIT 20
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "issssssiii", 
            $singleUserId, 
            $targetGender, 
            $targetGender, 
            $targetGender, 
            $targetGender, 
            $targetGender, 
            $targetGender, 
            $minAge, 
            $maxAge, 
            $singleUserId
        );
        $stmt->execute();
        $res = $stmt->get_result();

        $candidates = [];
        while ($row = $res->fetch_assoc()) {
            $photos = [];
            
            if (!empty($row['picture_url'])) {
                $photos[] = $row['picture_url'];
            }
            
            if (!empty($row['gallery_photos'])) {
                $galleryArr = explode(',', $row['gallery_photos']);
                foreach ($galleryArr as $gPhoto) {
                    $gPhoto = trim($gPhoto);
                    if (!empty($gPhoto) && !in_array($gPhoto, $photos)) {
                        $photos[] = $gPhoto;
                    }
                }
            }

            $candidates[] = [
                'candidate_id' => $row['user_id'],
                'name'        => $row['full_name'],
                'photos'      => $photos,
                'age'         => $row['age'] ?? '-',
                'location'    => $row['location'] ?? '-',
                'gender'      => $row['gender'] ?? '-',
                'occupation'  => $row['occupation'] ?? '-',
                'hobbies'     => $row['hobbies'] ?? '-',
                'bio'         => $row['bio'] ?? 'No bio set yet.',
                'lookingFor'  => $row['hook'] ?? 'Not specified.'
            ];
        }
        $stmt->close();

        return $candidates;
    }



    // Retrieves candidate still awaiting for Matchmaker's review
    public function getPendingCandidates(int $singleUserId): array {
        $stmt = $this->db->prepare("
            SELECT v.vouching_id,
                   p.full_name,
                   p.picture_url,
                   p.location,
                   p.gender,
                   p.occupation,
                   p.hobbies,
                   p.bio,
                   p.hook,
                   (YEAR(CURDATE()) - CAST(p.birth_year AS UNSIGNED)) AS age
            FROM Vouching v
            JOIN Profiles p ON v.candidate_user_id = p.user_id
            WHERE v.requesting_single_id = ? AND v.status = 'pending'
            ORDER BY v.timestamp ASC
        ");
        $stmt->bind_param("i", $singleUserId);
        $stmt->execute();
        $res = $stmt->get_result();

        $candidates = [];
        while ($row = $res->fetch_assoc()) {
            $candidates[] = [
                'vouching_id' => $row['vouching_id'],
                'name' => $row['full_name'],
                'photo' => $row['picture_url'] ?? null,
                'age' => $row['age'] ?? '-',
                'location' => $row['location'] ?? '-',
                'gender' => $row['gender'] ?? '-',
                'occupation' => $row['occupation'] ?? '-',
                'hobbies' => $row['hobbies'] ?? '-',
                'bio' => $row['bio'] ?? 'No bio set yet.',
                'lookingFor' => $row['hook'] ?? 'Not specified.'
            ];
        }
        $stmt->close();
        return $candidates;
    }

    // Records a matchmaker's Vouch or Veto decision on a candidate
    public function reviewCandidate(int $vouchingId, int $matchmakerUserId, string $status, ?string $note): bool {
        $check = $this->db->prepare("
            SELECT v.vouching_id 
            FROM Vouching v
            JOIN Account_Linking al ON al.single_user_id = v.requesting_single_id
            WHERE v.vouching_id = ? AND al.matchmaker_user_id = ?
        ");
        $check->bind_param("ii", $vouchingId, $matchmakerUserId);
        $check->execute();
        $owns = $check->get_result()->fetch_assoc();
        $check->close();

        if (!$owns) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE Vouching 
            SET status = ?, matchmaker_note = ? 
            WHERE vouching_id = ?
        ");
        $stmt->bind_param("ssi", $status, $note, $vouchingId);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}