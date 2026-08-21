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

$matchmakerUserId = (int)$_SESSION['user_id'];
$vouchingId = (int)($_POST['vouching_id'] ?? 0);
$action = $_POST['action'] ?? '';
$note = trim($_POST['note'] ?? '');

if (!$vouchingId || !in_array($action, ['vouch', 'veto'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

$status = $action === 'vouch' ? 'accepted' : 'rejected';

$db = Database::getConnection();
$vouchingModel = new Vouching($db);

$success = $vouchingModel->reviewCandidate($vouchingId, $matchmakerUserId, $status, $note ?: null);

echo json_encode(['success' => $success]);
exit();