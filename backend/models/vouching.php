<?php
class Vouching {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
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
                   TIMESTAMPDIFF(YEAR, STR_TO_DATE(p.birth_year, '%Y'), CURDATE()) as age
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