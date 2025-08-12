<?php
require 'includes/db.php';

$token = $_GET['token'] ?? '';
if ($token) {
  $stmt = $conn->prepare("SELECT user_id, expires_at FROM tokens WHERE token = ? AND type = 'verify'");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows === 1) {
    $stmt->bind_result($uid, $exp);
    $stmt->fetch();
    if (strtotime($exp) > time()) {
      $conn->query("UPDATE users SET is_verified = 1 WHERE id = $uid");
      $conn->query("DELETE FROM tokens WHERE user_id = $uid AND type = 'verify'");
      $msg = "Email verified! You can now login.";
    } else {
      $msg = "Token expired.";
    }
  } else {
    $msg = "Invalid token.";
  }
} else {
  $msg = "No token provided.";
}
?>
<!DOCTYPE html>
<html>
<head><title>Verify</title></head>
<body>
  <h2>Email Verification</h2>
  <p><?= $msg ?></p>
  <p><a href="login.php">Login</a></p>
</body>
</html>
