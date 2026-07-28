<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/accountLinking.php';

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$db = Database::getConnection();
$linkingModel = new AccountLinking($db);
$newCode = $linkingModel->generateNewCode($_SESSION['userId']);

echo json_encode(['code' => $newCode]);
exit();