<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../backend/controller/authController.php';

$auth = new AuthController();
$errors = [];
$activeForm = 'login';

// Handles form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'login') {
      $activeForm = 'login';
      $res = $auth->login($_POST['email'] ?? '', $_POST['password'] ?? '');
      if (isset($res['error'])) $errors[] = $res['error'];
    } elseif ($action === 'signup') {
      $activeForm = 'signup';
      $res = $auth->signup($_POST['name'] ?? '', $_POST['email'] ?? '', $_POST['password'] ?? '');
      if (isset($res['error'])) $errors[] = $res['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vouch Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<div class="card">
  <div class="artPanel">
    <div class="artFiller">
      <div class="logo"><img src="../assets/images/blackLogo.png" alt="vouch logo"></div>
      <div class="artTagline">Skip the bad setups.<br>Trust your <span>inner circle</span>.</div>
    </div>
  </div>

  <div class="formPanel">
    
    <!-- Login -->
    <div id="loginForm" class="formBlock <?= $activeForm === 'login' ? 'active' : '' ?>">
      <div class="logoInline"><img src="../assets/images/blackLogo.png" alt="vouch logo"></div>
      <h1>Welcome Back!</h1>
      <form method="POST" action="">
        <input type="hidden" name="action" value="login">
        <div class="field"><input type="email" name="email" placeholder="Email" required></div>
        <div class="field"><input type="password" name="password" placeholder="Password" required></div>
        <?php if ($activeForm === 'login' && !empty($errors)): ?>
          <div class="alert alertError"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
        <?php endif; ?>
        <button type="submit" class="submitBtn">Login</button>
      </form>
      <div class="switchLine">Don't Have an Account? <button type="button" onclick="showForm('signup')">Sign Up</button></div>
    </div>




    
    <!-- Sign Up -->
    <div id="signupForm" class="formBlock <?= $activeForm === 'signup' ? 'active' : '' ?>">
      <div class="logoInline"><img src="../assets/images/blackLogo.png" alt="vouch logo"></div>
      <h1>Create An Account</h1>
      <form method="POST" action="">
        <input type="hidden" name="action" value="signup">
        <div class="field"><input type="text" name="name" placeholder="Name" required></div>
        <div class="field"><input type="email" name="email" placeholder="Email" required></div>
        <div class="field"><input type="password" name="password" placeholder="Password" required></div>
        <?php if ($activeForm === 'signup' && !empty($errors)): ?>
          <div class="alert alertError"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
        <?php endif; ?>
        <button type="submit" class="submitBtn">Sign Up</button>
      </form>
      <div class="switchLine">Already Have an Account? <button type="button" onclick="showForm('login')">Login</button></div>
    </div>
  </div>
</div>

<script>
  function showForm(which) {
    document.getElementById('loginForm').classList.remove('active');
    document.getElementById('signupForm').classList.remove('active');
    document.getElementById(which === 'login' ? 'loginForm' : 'signupForm').classList.add('active');
  }
</script>
</body>
</html>