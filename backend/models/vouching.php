<?php
function hobbyLabel(?string $key): string {
    static $labels = [
        'art' => 'Art & Creative',
        'thrifting' => 'Thrifting & Vintage',
        'plants' => 'Plants & Gardening',
        'outdoors' => 'Hiking & Camping',
        'reading' => 'Reading & Bookstores',
        'music' => 'Music & Concerts',
        'travel' => 'Travel',
        'fitness' => 'Fitness & Sports',
        'cooking' => 'Cooking & Baking',
        'gaming' => 'Gaming',
        'coffee' => 'Coffee & Cafe Hopping',
    ];

    if (!$key) return '-';
    return $labels[$key] ?? $key;
}

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
                'hobbies'     => hobbyLabel($row['hobbies']),
                'bio'         => $row['bio'] ?? 'No bio set yet.',
                'lookingFor'  => $row['hook'] ?? 'Not specified.'
            ];
        }
        $stmt->close();

        return $candidates;
    }



    

    // Creates a new pending vouch request from a Single, tied to both Matchmakers involved
    public function requestVouch(int $singleUserId, int $candidateUserId): array {
        $check = $this->db->prepare("
            SELECT vouching_id FROM Vouching 
            WHERE requesting_single_id = ? AND candidate_user_id = ?
        ");
        $check->bind_param("ii", $singleUserId, $candidateUserId);
        $check->execute();
        if ($check->get_result()->fetch_assoc()) {
            $check->close();
            return ['error' => 'You already requested a vouch for this candidate.'];
        }
        $check->close();

        // sender_matchmaker_id: the requesting Single's own Matchmaker — they're the one who'll actually review this
        $senderStmt = $this->db->prepare("
            SELECT matchmaker_user_id FROM Account_Linking 
            WHERE single_user_id = ? AND matchmaker_user_id IS NOT NULL
        ");
        $senderStmt->bind_param("i", $singleUserId);
        $senderStmt->execute();
        $senderRow = $senderStmt->get_result()->fetch_assoc();
        $senderStmt->close();

        if (!$senderRow) {
            return ['error' => "You don't have a Matchmaker linked yet."];
        }
        $senderMatchmakerId = (int)$senderRow['matchmaker_user_id'];

        // reciever_matchmaker_id: the candidate's own Matchmaker, if they have one linked
        $receiverStmt = $this->db->prepare("
            SELECT matchmaker_user_id FROM Account_Linking 
            WHERE single_user_id = ? AND matchmaker_user_id IS NOT NULL
        ");
        $receiverStmt->bind_param("i", $candidateUserId);
        $receiverStmt->execute();
        $receiverRow = $receiverStmt->get_result()->fetch_assoc();
        $receiverStmt->close();
        $receiverMatchmakerId = $receiverRow ? (int)$receiverRow['matchmaker_user_id'] : null;

        $stmt = $this->db->prepare("
            INSERT INTO Vouching (sender_matchmaker_id, reciever_matchmaker_id, requesting_single_id, candidate_user_id, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iiii", $senderMatchmakerId, $receiverMatchmakerId, $singleUserId, $candidateUserId);
        $success = $stmt->execute();
        $stmt->close();

        return $success ? ['success' => true] : ['error' => 'Something went wrong saving your request.'];
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
                (YEAR(CURDATE()) - CAST(p.birth_year AS UNSIGNED)) AS age,
                GROUP_CONCAT(ph.photo_url ORDER BY ph.photo_id ASC SEPARATOR ',') AS gallery_photos
            FROM Vouching v
            JOIN Profiles p ON v.candidate_user_id = p.user_id
            LEFT JOIN Profile_Photos ph ON ph.user_id = p.user_id
            WHERE v.requesting_single_id = ? AND v.status = 'pending'
            GROUP BY v.vouching_id
            ORDER BY v.timestamp ASC
        ");
        $stmt->bind_param("i", $singleUserId);
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
                'vouching_id' => $row['vouching_id'],
                'name' => $row['full_name'],
                'photo' => $row['picture_url'] ?? null,
                'photos' => $photos,
                'age' => $row['age'] ?? '-',
                'location' => $row['location'] ?? '-',
                'gender' => $row['gender'] ?? '-',
                'occupation' => $row['occupation'] ?? '-',
                'hobbies' => hobbyLabel($row['hobbies']),
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
            SELECT v.vouching_id, v.requesting_single_id, v.candidate_user_id, v.reciever_matchmaker_id
            FROM Vouching v
            JOIN Account_Linking al ON al.single_user_id = v.requesting_single_id
            WHERE v.vouching_id = ? AND al.matchmaker_user_id = ?
        ");
        $check->bind_param("ii", $vouchingId, $matchmakerUserId);
        $check->execute();
        $vouchRow = $check->get_result()->fetch_assoc();
        $check->close();

        if (!$vouchRow) {
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

        if ($success && $status === 'accepted') {
            $requestingSingleId = (int)$vouchRow['requesting_single_id'];
            $candidateUserId    = (int)$vouchRow['candidate_user_id'];

            // Forward to the candidate's own matchmaker so they get a turn too
            file_put_contents(__DIR__ . '/../../debug.log', date('Y-m-d H:i:s') . " review: reciever_matchmaker_id=" . var_export($vouchRow['reciever_matchmaker_id'], true) . "\n", FILE_APPEND);
            if (!empty($vouchRow['reciever_matchmaker_id'])) {
                $this->forwardProfileToReceivingMatchmaker($candidateUserId, $requestingSingleId);
            }

            // if the other side already vouched back, mark both rows as a mutual match
            $this->checkAndMarkMutualMatch($vouchingId, $requestingSingleId, $candidateUserId);
        }

        return $success;
    }

    // Creates a new pending Vouching entry so the candidate's Matchmaker can review the original requesting Single
    private function forwardProfileToReceivingMatchmaker(int $candidateUserId, int $originalSingleId): void {
        $debugPath = __DIR__ . '/../../debug.log';
        file_put_contents($debugPath, date('Y-m-d H:i:s') . " forward: called with candidate=$candidateUserId original=$originalSingleId\n", FILE_APPEND);

        $check = $this->db->prepare("
            SELECT vouching_id FROM Vouching 
            WHERE requesting_single_id = ? AND candidate_user_id = ?
        ");
        $check->bind_param("ii", $candidateUserId, $originalSingleId);
        $check->execute();
        if ($row = $check->get_result()->fetch_assoc()) {
            $check->close();
            file_put_contents($debugPath, date('Y-m-d H:i:s') . " forward: BLOCKED - existing row already at vouching_id={$row['vouching_id']}\n", FILE_APPEND);
            return; 
        }
        $check->close();

        $senderStmt = $this->db->prepare("
            SELECT matchmaker_user_id FROM Account_Linking 
            WHERE single_user_id = ? AND matchmaker_user_id IS NOT NULL
        ");
        $senderStmt->bind_param("i", $candidateUserId);
        $senderStmt->execute();
        $senderRow = $senderStmt->get_result()->fetch_assoc();
        $senderStmt->close();

        if (!$senderRow) {
            file_put_contents($debugPath, date('Y-m-d H:i:s') . " forward: BLOCKED - no linked matchmaker found for candidate=$candidateUserId\n", FILE_APPEND);
            return;
        }

        file_put_contents($debugPath, date('Y-m-d H:i:s') . " forward: INSERTING pending row, matchmaker={$senderRow['matchmaker_user_id']}\n", FILE_APPEND);

        $stmt = $this->db->prepare("
            INSERT INTO Vouching (sender_matchmaker_id, reciever_matchmaker_id, requesting_single_id, candidate_user_id, status)
            VALUES (?, NULL, ?, ?, 'pending')
        ");
        $matchmakerId = (int)$senderRow['matchmaker_user_id'];
        $stmt->bind_param("iii", $matchmakerId, $candidateUserId, $originalSingleId);
        $stmt->execute();
        $stmt->close();
    }

    // If the reciprocal row (B->A) is also 'accepted', flip both rows to 'matched'
    private function checkAndMarkMutualMatch(int $vouchingId, int $requestingSingleId, int $candidateUserId): void {
        $stmt = $this->db->prepare("
            SELECT vouching_id FROM Vouching
            WHERE requesting_single_id = ? AND candidate_user_id = ? AND status = 'accepted'
        ");
        $stmt->bind_param("ii", $candidateUserId, $requestingSingleId);
        $stmt->execute();
        $reciprocal = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($reciprocal) {
            $reciprocalId = (int)$reciprocal['vouching_id'];
            $update = $this->db->prepare("UPDATE Vouching SET status = 'matched' WHERE vouching_id IN (?, ?)");
            $update->bind_param("ii", $vouchingId, $reciprocalId);
            $update->execute();
            $update->close();
        }
    }
}