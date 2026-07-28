<?php
class Profile {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    // Retrieves the display name for a user
    public function getDisplayName(int $userId): string {
        $stmt = $this->db->prepare("SELECT full_name FROM Profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res['full_name'] ?? 'User';
    }

    // Updates the profile information for a Single user
    public function updateSingleProfile(int $userId, array $data, ?string $photoPath): void {
        if ($photoPath) {
            $stmt = $this->db->prepare("UPDATE Profiles SET birth_year=?, gender=?, location=?, occupation=?, hobbies=?, bio=?, hook=?, picture_url=? WHERE user_id=?");
            $stmt->bind_param("isssssssi", $data['birthYear'], $data['gender'], $data['location'], $data['occupation'], $data['hobbies'], $data['bio'], $data['hook'], $photoPath, $userId);
        } else {
            $stmt = $this->db->prepare("UPDATE Profiles SET birth_year=?, gender=?, location=?, occupation=?, hobbies=?, bio=?, hook=? WHERE user_id=?");
            $stmt->bind_param("issssssi", $data['birthYear'], $data['gender'], $data['location'], $data['occupation'], $data['hobbies'], $data['bio'], $data['hook'], $userId);
        }
        $stmt->execute();
        $stmt->close();
    }

    // Updates the profile information for a Matchmaker user
    public function updateMatchmakerProfile(int $userId, array $data, ?string $photoPath): void {
        if ($photoPath) {
            $stmt = $this->db->prepare("UPDATE Profiles SET relationship_to_single=?, credentials=?, picture_url=? WHERE user_id=?");
            $stmt->bind_param("sssi", $data['relationship'], $data['credentials'], $photoPath, $userId);
        } else {
            $stmt = $this->db->prepare("UPDATE Profiles SET relationship_to_single=?, credentials=? WHERE user_id=?");
            $stmt->bind_param("ssi", $data['relationship'], $data['credentials'], $userId);
        }
        $stmt->execute();
        $stmt->close();
    }

    // Updates the dating preferences for a Single user
    public function updatePreferences(int $userId, string $targetGender, string $targetAges): void {
        $stmt = $this->db->prepare("INSERT INTO Singles_Preferences (user_id, target_gender, target_ages) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE target_gender = VALUES(target_gender), target_ages = VALUES(target_ages)");
        $stmt->bind_param("iss", $userId, $targetGender, $targetAges);
        $stmt->execute();
        $stmt->close();
    }

    // Retrieves the dating preferences for a Single user
    public function addGalleryPhoto(int $userId, string $photoUrl): void {
        $stmt = $this->db->prepare("INSERT INTO Profile_Photos (user_id, photo_url) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $photoUrl);
        $stmt->execute();
        $stmt->close();
    }
}