<?php
class AccountLinking {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    // Ensures a Single user has a unique code
    public function ensureCode(int $userId): string {
        $check = $this->db->prepare("SELECT link_code FROM Account_Linking WHERE single_user_id = ? AND matchmaker_user_id IS NULL");
        $check->bind_param("i", $userId);
        $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        $check->close();

        return $existing ? $existing['link_code'] : $this->generateNewCode($userId);
    }

    // Generates a new unique code for a Single user
    public function generateNewCode(int $userId): string {
        // Delete older unclaimed codes if any
        $del = $this->db->prepare("DELETE FROM Account_Linking WHERE single_user_id = ? AND matchmaker_user_id IS NULL");
        $del->bind_param("i", $userId);
        $del->execute();
        $del->close();

        $newCode = sprintf("%05d", random_int(0, 99999));
        $insert = $this->db->prepare("INSERT INTO Account_Linking (single_user_id, link_code) VALUES (?, ?)");
        $insert->bind_param("is", $userId, $newCode);
        $insert->execute();
        $insert->close();

        return $newCode;
    }

    // Claims a code for a Matchmaker user
    public function claimCode(int $matchmakerId, string $code): bool {
        $stmt = $this->db->prepare("SELECT link_id FROM Account_Linking WHERE link_code = ? AND matchmaker_user_id IS NULL");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $link = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$res || $res['single_user_id'] == $matchmakerUserId) {
            return false;
        }

        $claim = $this->db->prepare("UPDATE Account_Linking SET matchmaker_user_id = ? WHERE link_id = ?");
        $claim->bind_param("ii", $matchmakerId, $link['link_id']);
        $claim->execute();
        $claim->close();

        return true;
    }

}