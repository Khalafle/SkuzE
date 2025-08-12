<?php
require 'includes/auth.php';
require 'includes/db.php';

$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_contact'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $stmt = $conn->prepare("UPDATE users SET email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $phone, $id);
    $stmt->execute();
    $msg = "Profile updated.";
  } elseif (isset($_POST['update_password'])) {
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    if ($new !== $confirm) {
      $error = "Passwords do not match.";
    } elseif (strlen($new) < 6) {
      $error = "Password too short.";
    } else {
      $hash = password_hash($new, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
      $stmt->bind_param("si", $hash, $id);
      $stmt->execute();
      $msg = "Password updated.";
    }
  }
}

$stmt = $conn->prepare("SELECT email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($email, $phone);
$stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Profile</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>Edit Profile</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

  <h3>Contact Info</h3>
  <form method="post">
    <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" required placeholder="Email">
    <input type="text" name="phone" value="<?= htmlspecialchars($phone); ?>" placeholder="Phone">
    <button type="submit" name="update_contact">Save</button>
  </form>

  <h3>Change Password</h3>
  <form method="post">
    <input type="password" name="new_password" required placeholder="New Password">
    <input type="password" name="confirm_password" required placeholder="Confirm Password">
    <button type="submit" name="update_password">Update Password</button>
  </form>

  <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
