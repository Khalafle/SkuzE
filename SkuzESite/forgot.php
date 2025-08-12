<?php
session_start();
require 'includes/db.php';
// Mail helper lives in the project root
require 'mail.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    $msg = "Invalid CSRF token.";
  } else {
    $email = trim($_POST['email']);

  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($uid);
    $stmt->fetch();

    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);

    $stmt = $conn->prepare("INSERT INTO tokens (user_id, token, type, expires_at) VALUES (?, ?, 'reset', ?)");
    $stmt->bind_param("iss", $uid, $token, $expires);
    $stmt->execute();

    $link = "https://skuze.tech/reset.php?token=$token";
    $body = "To reset your password, visit: $link\nThis link expires in 1 hour.";
    send_email($email, "SkuzE Password Reset", $body);

    $msg = "Reset link sent if account exists.";
    } else {
      $msg = "Reset link sent if account exists.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head><title>Forgot Password</title></head>
<body>
  <h2>Forgot Password</h2>
  <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
  <form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="email" name="email" required placeholder="Your email">
    <button type="submit">Send Reset Link</button>
  </form>
</body>
</html>
