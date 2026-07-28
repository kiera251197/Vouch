<?php
class User {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT U.user_id, U.password, U.user_role, P.full_name FROM Users U LEFT JOIN Profiles P ON U.user_id = P.user_id WHERE U.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    public function registerUser(string $fullName, string $email, string $password): ?int {
        try {
            $this->db->begin_transaction();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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

    public function updateRole(int $userId, string $role): void {
        $stmt = $this->db->prepare("UPDATE Users SET user_role = ? WHERE user_id = ?");
        $stmt->bind_param("si", $role, $userId);
        $stmt->execute();
        $stmt->close();
    }
}