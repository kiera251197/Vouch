<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$db = new mysqli("localhost", "root", "", "vouch_db");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$errors = [];
$success = '';
$activeForm = 'login'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // Login
    if ($action === 'login') {
        $activeForm = 'login';

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $errors[] = 'Please fill in both email and password';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }

        if (empty($errors)) {
            $stmt = $db->prepare("SELECT user_id, password, user_role FROM Users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['userId'] = $user['user_id'];
                    $_SESSION['userRole'] = $user['user_role'];
                    
                    if ($user['user_role'] === 'single') {
                        header("Location: singleDashboard.php");
                    } else {
                        header("Location: matchmakerDashboard.php");
                    }
                    exit();
                } else {
                    $errors[] = 'Invalid password entered.';
                }
            } else {
                $errors[] = 'No account found with that email.';
            }
            $stmt->close();
        }

    // Sign Up
    } elseif ($action === 'signup') {
        $activeForm = 'signup';

        $fullName = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $userRole = $_POST['userRole'] ?? '';

        if ($fullName === '' || $email === '' || $password === '' || $userRole === '') {
            $errors[] = 'Please fill in all fields, including choosing a role.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if (empty($errors)) {
            // Check if email already exists
            $checkEmail = $db->prepare("SELECT user_id FROM Users WHERE email = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $checkResult = $checkEmail->get_result();

            if ($checkResult->num_rows > 0) {
                $errors[] = 'This email is already registered.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert into Users Table
                $insertUser = $db->prepare("INSERT INTO Users (email, password, user_role) VALUES (?, ?, ?)");
                $insertUser->bind_param("sss", $email, $hashedPassword, $userRole);

                if ($insertUser->execute()) {
                    $newUserId = $insertUser->insert_id;

                    // Insert display name into Profiles Table
                    $insertProfile = $db->prepare("INSERT INTO Profiles (user_id, full_name) VALUES (?, ?)");
                    $insertProfile->bind_param("is", $newUserId, $fullName);
                    $insertProfile->execute();
                    $insertProfile->close();

                    $success = 'Account created successfully! You can now log in.';
                    $activeForm = 'login';
                } else {
                    $errors[] = 'Something went wrong during registration. Please try again.';
                }
                $insertUser->close();
            }
            $checkEmail->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login/Sign Up</title>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="index.css">
</head>
<body>

<div class="card">

  <div class="artPanel">
    <div class="artFiller">
      <div class="logo">
        <img src="../assets/images/blackLogo.png" alt="vouch logo">
      </div>
      <div class="artTagline">
        Skip the bad setups.<br>
        Trust your <span>inner circle</span>.
      </div>
    </div>
  </div>

  <div class="formPanel">

    <!-- Login -->
    <div id="loginForm" class="formBlock <?= $activeForm === 'login' ? 'active' : '' ?>">
      <div class="logoInline">
        <img src="../assets/images/blackLogo.png" alt="vouch logo">
      </div>
      <h1>Welcome Back!</h1>

      <?php if ($activeForm === 'login' && !empty($errors)): ?>
        <div class="alert alertError">
          <?= htmlspecialchars(implode(' ', $errors)) ?>
        </div>
      <?php endif; ?>

      <?php if ($activeForm === 'login' && $success): ?>
        <div class="alert alertSuccess"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <input type="hidden" name="action" value="login">

        <div class="field">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="field">
          <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="submitBtn">Login</button>
      </form>

      <div class="switchLine">
        Don't Have an Account?
        <button type="button" onclick="showForm('signup')">Sign Up</button>
      </div>
    </div>

    <!-- Sign Up -->
    <div id="signupForm" class="formBlock <?= $activeForm === 'signup' ? 'active' : '' ?>">
      <div class="logoInline">
        <img src="../assets/images/blackLogo.png" alt="vouch logo">
      </div>
      <h1>Create An Account</h1>

      <?php if ($activeForm === 'signup' && !empty($errors)): ?>
        <div class="alert alertError">
          <?= htmlspecialchars(implode(' ', $errors)) ?>
        </div>
      <?php endif; ?>

      <?php if ($activeForm === 'signup' && $success): ?>
        <div class="alert alertSuccess"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <input type="hidden" name="action" value="signup">

        <div class="field">
          <input type="text" name="name" placeholder="Name" required>
        </div>
        <div class="field">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="field">
          <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="submitBtn">Sign Up</button>
      </form>

      <div class="switchLine">
        Already Have an Account?
        <button type="button" onclick="showForm('login')">Login</button>
      </div>
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