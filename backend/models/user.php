<?php
class User {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    // Finds a user by their email address
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT u.user_id, u.email, u.password, u.user_role, p.full_name FROM Users u LEFT JOIN Profiles p ON u.user_id = p.user_id WHERE u.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user ?: null;
    }

    // Registers a new user and creates a profile for them
    public function registerUser(string $fullName, string $email, string $password): ?int {
        try {
            $this->db->begin_transaction();

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->db->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashedPassword);
            $stmt->execute();
            $newUserId = $stmt->insert_id;
            $stmt->close();

            $pStmt = $this->db->prepare("INSERT INTO Profiles (user_id, full_name) VALUES (?, ?)");
            $pStmt->bind_param("is", $newUserId, $fullName);
            $pStmt->execute();
            $pStmt->close();

            $this->db->commit();
            return $newUserId;
        } catch (Exception $e) {
            $this->db->rollback();
            return null;
        }
    }

    // Updates the role of a user
    public function updateRole(int $userId, string $role): bool {
        $stmt = $this->db->prepare("UPDATE Users SET user_role = ? WHERE user_id = ?");
        $stmt->bind_param("si", $role, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
