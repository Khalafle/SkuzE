<?php
session_start();
require 'includes/db.php';
require 'includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username']);
  $email = trim($_POST['email']);
  $pass = $_POST['password'];

  if (strlen($user) < 3 || strlen($pass) < 6) {
    $error = "Username must be 3+ chars and password 6+.";
  } else {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $user, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = "Username or email already exists.";
    } else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $user, $email, $hash);
      $stmt->execute();

      $uid = $stmt->insert_id;
      $token = bin2hex(random_bytes(32));
      $expires = date('Y-m-d H:i:s', time() + 3600);
      $stmt = $conn->prepare("INSERT INTO tokens (user_id, token, type, expires_at) VALUES (?, ?, 'verify', ?)");
      $stmt->bind_param("iss", $uid, $token, $expires);
      $stmt->execute();

      $link = "https://skuze.tech/verify.php?token=$token";
      $body = "Welcome to SkuzE!\n\nPlease verify your email: $link";
      send_email($email, "Verify your account", $body);

      $success = "Check your email to verify your account.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>Register</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
  <form method="post">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Register</button>
  </form>
</body>
</html>
