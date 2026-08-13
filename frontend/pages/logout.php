<?php
// logout.php
session_start();

// 1. Unset all session values in memory
$_SESSION = [];

// 2. Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy session and force redirect to login
session_destroy();
header("Location: index.php"); 
exit();