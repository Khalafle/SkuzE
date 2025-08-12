<?php
require 'includes/auth.php';
require 'includes/db.php';

$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <a class="logo-link" href="index.php">
    <img src="assets/logo.png" alt="SkuzE Logo">
  </a>

  <h2>Welcome, <?= htmlspecialchars($username) ?></h2>
  <p><a href="services.php">Start a Service Request</a></p>
  <p><a href="my-requests.php">View My Service Requests</a></p>
  <p><a href="profile.php">Edit Profile</a></p>
  <p><a href="logout.php">Logout</a></p>
</body>
</html>
