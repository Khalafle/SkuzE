<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username']);
  $pass = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, password, is_verified, is_admin FROM users WHERE username = ?");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $hash, $verified, $admin);
    $stmt->fetch();

    if (!password_verify($pass, $hash)) {
      $error = "Incorrect password.";
    } elseif (!$verified) {
      $error = "Please verify your email first.";
    } else {
      $_SESSION['user_id'] = $id;
      $_SESSION['is_admin'] = $admin;
      header("Location: dashboard.php");
      exit;
    }
  } else {
    $error = "User not found.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>Login</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="post">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
  </form>
  <p><a href="forgot.php">Forgot Password?</a></p>
</body>
</html>
