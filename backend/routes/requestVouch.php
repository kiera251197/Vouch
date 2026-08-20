<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/vouching.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorised']);
    exit();
}

$singleUserId = (int)$_SESSION['user_id'];
$candidateUserId = (int)($_POST['candidate_user_id'] ?? 0);

if (!$candidateUserId) {
    echo json_encode(['error' => 'Invalid candidate']);
    exit();
}

$db = Database::getConnection();
$vouchingModel = new Vouching($db);

$result = $vouchingModel->requestVouch($singleUserId, $candidateUserId);

echo json_encode($result);
exit();