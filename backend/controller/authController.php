<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        $db = Database::getConnection();
        $this->userModel = new User($db);
    }

    // Handles user login
    public function login(string $email, string $password): array {
        if (empty($email) || empty($password)) {
            return ['error' => 'Please fill in both email and password'];
        }

        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['userId'] = $user['user_id'];
            $_SESSION['userRole'] = $user['user_role'];
            $_SESSION['fullName'] = $user['full_name'];

            if (empty($user['user_role'])) {
                header("Location: setupProfile.php");
            } elseif ($user['user_role'] === 'single') {
                header("Location: singleDashboard.php");
            } else {
                header("Location: dashboardMatchmaker.php");
            }
            exit();
        }

        return ['error' => 'Invalid email or password'];
    }

    // Handles user sign up
    public function signup(string $fullName, string $email, string $password): array {
        if (empty($fullName) || empty($email) || empty($password)) {
            return ['error' => 'Please fill in all fields'];
        }
        if (strlen($password) < 6) {
            return ['error' => 'Password must be at least 6 characters'];
        }

        if ($this->userModel->findByEmail($email)) {
            return ['error' => 'This email is already registered'];
        }

        $newUserId = $this->userModel->registerUser($fullName, $email, $password);
        if ($newUserId) {
            $_SESSION['userId'] = $newUserId;
            $_SESSION['fullName'] = $fullName;
            header("Location: setupProfile.php");
            exit();
        }

        return ['error' => 'Registration failed. Try again.'];
    }
}