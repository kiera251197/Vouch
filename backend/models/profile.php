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
        $birthYear  = !empty($data['birthYear']) ? (string)$data['birthYear'] : null;
        $gender     = !empty($data['gender']) ? $data['gender'] : null;
        $location   = !empty($data['location']) ? $data['location'] : null;
        $occupation = !empty($data['occupation']) ? $data['occupation'] : null;
        $hobbies    = !empty($data['hobbies']) ? $data['hobbies'] : null;
        $bio        = !empty($data['bio']) ? $data['bio'] : null;
        $hook       = !empty($data['hook']) ? $data['hook'] : null;
        $fullName   = $_SESSION['userName'] ?? $_SESSION['full_name'] ?? 'User';

        if ($photoPath) {
            $stmt = $this->db->prepare("
                INSERT INTO Profiles (user_id, full_name, birth_year, gender, location, occupation, hobbies, bio, hook, picture_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    birth_year  = COALESCE(VALUES(birth_year), birth_year),
                    gender      = COALESCE(VALUES(gender), gender),
                    location    = COALESCE(VALUES(location), location),
                    occupation  = COALESCE(VALUES(occupation), occupation),
                    hobbies     = COALESCE(VALUES(hobbies), hobbies),
                    bio         = COALESCE(VALUES(bio), bio),
                    hook        = COALESCE(VALUES(hook), hook),
                    picture_url = VALUES(picture_url)
            ");
            $stmt->bind_param("isssssssss", $userId, $fullName, $birthYear, $gender, $location, $occupation, $hobbies, $bio, $hook, $photoPath);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO Profiles (user_id, full_name, birth_year, gender, location, occupation, hobbies, bio, hook)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    birth_year  = COALESCE(VALUES(birth_year), birth_year),
                    gender      = COALESCE(VALUES(gender), gender),
                    location    = COALESCE(VALUES(location), location),
                    occupation  = COALESCE(VALUES(occupation), occupation),
                    hobbies     = COALESCE(VALUES(hobbies), hobbies),
                    bio         = COALESCE(VALUES(bio), bio),
                    hook        = COALESCE(VALUES(hook), hook)
            ");
            $stmt->bind_param("issssssss", $userId, $fullName, $birthYear, $gender, $location, $occupation, $hobbies, $bio, $hook);
        }
        $stmt->execute();
        $stmt->close();
    }

    // Updates the profile information for a Matchmaker user
    public function updateMatchmakerProfile(int $userId, array $data, ?string $photoPath): void {
        $relationship = trim($data['relationship'] ?? '');
        $credentials  = trim($data['credentials'] ?? $data['linkCode'] ?? '');
        $fullName     = $_SESSION['userName'] ?? $_SESSION['full_name'] ?? 'User';
        
        if ($photoPath) {
            $stmt = $this->db->prepare("
                INSERT INTO Profiles (user_id, full_name, relationship_to_single, credentials, picture_url)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    relationship_to_single = VALUES(relationship_to_single),
                    credentials            = VALUES(credentials),
                    picture_url            = VALUES(picture_url)
            ");
            $stmt->bind_param("issss", $userId, $fullName, $relationship, $credentials, $photoPath);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO Profiles (user_id, full_name, relationship_to_single, credentials)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    relationship_to_single = VALUES(relationship_to_single),
                    credentials            = VALUES(credentials)
            ");
            $stmt->bind_param("isss", $userId, $fullName, $relationship, $credentials);
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

    public function saveSingleProfile($userId, $data, $photoPath = null) {
    $checkStmt = $this->db->prepare("SELECT profile_id FROM Profiles WHERE user_id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $existing = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();

    if ($existing) {
        $stmt = $this->db->prepare("
            UPDATE Profiles 
            SET birth_year = ?, 
                gender = ?, 
                location = ?, 
                occupation = ?, 
                hobbies = ?, 
                hook = ?, 
                bio = ?, 
                picture_url = COALESCE(?, picture_url)
            WHERE user_id = ?
        ");
        $stmt->bind_param(
            "ssssssssi", 
            $data['birthYear'], 
            $data['gender'], 
            $data['location'], 
            $data['occupation'], 
            $data['hobbies'], 
            $data['hook'], 
            $data['bio'], 
            $photoPath, 
            $userId
        );
    } else {
        $stmt = $this->db->prepare("
            INSERT INTO Profiles (user_id, full_name, birth_year, gender, location, occupation, hobbies, hook, bio, picture_url)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $fullName = $_SESSION['userName'] ?? '';
        $stmt->bind_param(
            "isssssssss", 
            $userId, 
            $fullName, 
            $data['birthYear'], 
            $data['gender'], 
            $data['location'], 
            $data['occupation'], 
            $data['hobbies'], 
            $data['hook'], 
            $data['bio'], 
            $photoPath
        );
    }

    $result = $stmt->execute();
    $stmt->close();
    return $result;
    }
}