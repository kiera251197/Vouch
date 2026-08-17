<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        $email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));

        if (empty($email) || empty($password)) {
            return ['error' => 'Please fill in both email and password.'];
        }

        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['userName'] = $user['full_name'] ?? 'User';
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_role'] = $user['user_role'];

            if (empty($user['user_role'])) {
                header("Location: setupProfile.php");
            } elseif ($user['user_role'] === 'matchmaker') {
                header("Location: dashboardMatchmaker.php");
            } else {
                header("Location: dashboardSingle.php");
            }
            exit();
        }
        
        return ['error' => 'Invalid email or password.'];
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
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['userName'] = $fullName;
            $_SESSION['email'] = $email;
            $_SESSION['user_role'] = null; 
            header("Location: setupProfile.php");
            exit();
        }

        return ['error' => 'Registration failed. Try again.'];
    }
   
}

