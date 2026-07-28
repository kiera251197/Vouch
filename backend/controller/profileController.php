<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/profile.php';
require_once __DIR__ . '/../models/accountLinking.php';

class ProfileController {
    public Profile $profileModel;
    public AccountLinking $linkingModel;
    public User $userModel;

    public function __construct() {
        $db = Database::getConnection();
        $this->profileModel = new Profile($db);
        $this->linkingModel = new AccountLinking($db);
        $this->userModel = new User($db);
    }

    // Handles the Single user setup process
    public function processSingleSetup(int $userId, array $postData, array $files): array {
        $birthYear    = $postData['birthYear'] ?? null;
        $gender       = $postData['gender'] ?? null;
        $location     = trim($postData['location'] ?? '');
        $targetAges   = $postData['targetAges'] ?? null;
        $targetGender = $postData['targetGender'] ?? null;

        if (!$birthYear || !$gender || $location === '') {
            return ['error' => 'Please fill in your birth year, gender, and location', 'step' => 'sStep2'];
        }
        if (!$targetAges || !$targetGender) {
            return ['error' => 'Please choose your dating preferences', 'step' => 'sStep3'];
        }

        $photoPath = $this->uploadFile($files['profilePhoto'] ?? null, $userId, 'profiles');

        $this->userModel->updateRole($userId, 'single');
        $this->profileModel->updateSingleProfile($userId, $postData, $photoPath);
        $this->profileModel->updatePreferences($userId, $targetGender, $targetAges);
        $this->handleGalleryUploads($userId, $files['galleryPhotos'] ?? null);

        header("Location: dashboardSingle.php");
        exit();
    }

    // Handles the Matchmaker user setup process
    public function processMatchmakerSetup(int $userId, array $postData, array $files): array {
        $relationship = trim($postData['relationship'] ?? '');
        $credentials  = trim($postData['credentials'] ?? '');
        $linkCode     = trim($postData['linkCode'] ?? '');

        if ($relationship === '' || $credentials === '') {
            return ['error' => 'Please fill in your relationship and credentials.', 'step' => 'mStep2'];
        }
        if ($linkCode === '' || !preg_match('/^\d{5}$/', $linkCode)) {
            return ['error' => 'Please enter the 5 digit code exactly as your Single gave it.', 'step' => 'mStep3'];
        }

        if (!$this->linkingModel->claimCode($userId, $linkCode)) {
            return ['error' => 'That code doesn\'t match an available Single. Try again.', 'step' => 'mStep3'];
        }

        $photoPath = $this->uploadFile($files['profilePhoto'] ?? null, $userId, 'profiles');
        $this->userModel->updateRole($userId, 'matchmaker');
        $this->profileModel->updateMatchmakerProfile($userId, $postData, $photoPath);

        header("Location: dashboardMatchmaker.php");
        exit();
    }

    // Handles file uploads for their profile photos
    private function uploadFile(?array $file, int $userId, string $folder): ?string {
        if (!$file || empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) return null;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return null;

        $destDir = __DIR__ . '/../../frontend/pages/uploads/' . $folder . '/';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        $filename = $userId . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $destDir . $filename);

        return 'uploads/' . $folder . '/' . $filename;
    }

    // Handles multiple gallery photo uploads for the Single users
    private function handleGalleryUploads(int $userId, ?array $files): void {
        if (!$files || empty($files['name'][0])) return;
        $count = min(count($files['name']), 5);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

            $destDir = __DIR__ . '/../../frontend/pages/uploads/gallery/';
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);

            $filename = $userId . '_' . time() . '_' . $i . '.' . $ext;
            move_uploaded_file($files['tmp_name'][$i], $destDir . $filename);

            $this->profileModel->addGalleryPhoto($userId, 'uploads/gallery/' . $filename);
        }
    }
}