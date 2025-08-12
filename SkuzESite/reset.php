<?php
session_start();
require 'includes/db.php';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$token = $_GET['token'] ?? '';
$valid = false;

if ($token) {
  $stmt = $conn->prepare("SELECT user_id, expires_at FROM tokens WHERE token = ? AND type = 'reset'");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($uid, $expires);
    $stmt->fetch();

    if (strtotime($expires) > time()) {
      $valid = true;
    } else {
      $error = "Reset token expired.";
    }
  } else {
    $error = "Invalid token.";
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
      $error = "Invalid CSRF token.";
    } else {
      $newpass = $_POST['new_password'];
      $confirm = $_POST['confirm_password'];

      if ($newpass !== $confirm) {
        $error = "Passwords do not match.";
      } elseif (strlen($newpass) < 6) {
        $error = "Password too short.";
      } else {
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $uid);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $msg = "Password reset. You may now <a href='login.php'>login</a>.";
        $valid = false;
      }
    }
  }
} else {
  $error = "No token provided.";
}
?>
<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
  <h2>Reset Password</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

  <?php if ($valid): ?>
  <form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="password" name="new_password" required placeholder="New password">
    <input type="password" name="confirm_password" required placeholder="Confirm new password">
    <button type="submit">Reset Password</button>
  </form>
  <?php endif; ?>
</body>
</html>
