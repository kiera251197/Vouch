<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/accountLinking.php';

// Checks if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'Unauthorised']);
    exit();
}

// Regenerates a new unique code for the logged in Single 
$db = Database::getConnection();
$linkingModel = new AccountLinking($db);
$newCode = $linkingModel->generateNewCode($_SESSION['userId']);

echo json_encode(['code' => $newCode]);
exit();