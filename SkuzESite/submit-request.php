<?php
require 'includes/auth.php';
require 'includes/db.php';
require_once 'mail.php';

$success = false;
$error = '';
$filename = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $category = $_POST['category'];
  $make = $_POST['make'] ?? null;
  $model = $_POST['model'] ?? null;
  $serial = $_POST['serial'] ?? null;
  $issue = $_POST['issue'] ?? '';
  $build = $_POST['build'] ?? 'no';
  $device_type = $_POST['device_type'] ?? null;

  // ✅ Handle optional file upload
  if (!empty($_FILES['photo']['name'])) {
    $upload_path = 'uploads/';
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . strtolower($ext);
    $target = $upload_path . $filename;

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
      $error = "Failed to upload image.";
    }
  }

  // Proceed if no file errors
  if (!$error) {
    $stmt = $conn->prepare("INSERT INTO service_requests 
      (user_id, category, make, model, serial, issue, build, device_type, photo)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
      $stmt->bind_param("issssssss", $user_id, $category, $make, $model, $serial, $issue, $build, $device_type, $filename);
      if ($stmt->execute()) {
        $success = true;

        // ✅ Send admin notification
        $adminEmail = 'owner@skuze.tech';
        $subject = "New Service Request Submitted";
        $body = "User ID: $user_id\nCategory: $category\nMake/Model: $make $model\nSerial: $serial\nBuild Request: $build\nDevice Type: $device_type\nIssue: $issue";
        if ($filename) {
          $body .= "\nPhoto: uploads/$filename";
        }
        send_email($adminEmail, $subject, $body);
      } else {
        $error = "Error executing query.";
      }
    } else {
      $error = "Database error.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Request Submitted</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php if ($success): ?>
    <h2>Request Submitted</h2>
    <p>Thank you! We'll review your request and get back to you shortly.</p>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
  <?php else: ?>
    <h2>Error</h2>
    <p><?= htmlspecialchars($error) ?></p>
    <p><a href="services.php">Try Again</a></p>
  <?php endif; ?>
</body>
</html>
