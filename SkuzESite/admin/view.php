<?php
require '../includes/auth.php';
require '../includes/db.php';

if (!$_SESSION['is_admin']) {
  header("Location: ../dashboard.php");
  exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) die("Invalid ID");

$stmt = $conn->prepare("SELECT r.*, u.username, u.email, u.phone, u.last_active FROM service_requests r
                        JOIN users u ON r.user_id = u.id WHERE r.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) die("Request not found");

$last_active = strtotime($data['last_active']);
$is_online = (time() - $last_active < 300); // 5 minutes
$online_status = $is_online ? "<span style='color:green;'>Online</span>" : "<span style='color:gray;'>Offline</span>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $status = $_POST['status'];
  $note = $_POST['admin_note'];

  $update = $conn->prepare("UPDATE service_requests SET status = ?, admin_note = ? WHERE id = ?");
  $update->bind_param("ssi", $status, $note, $id);
  $update->execute();

  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Request</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <h2>Request #<?= $data['id'] ?></h2>
  <p><strong>User:</strong> <?= htmlspecialchars($data['username']) ?> (<?= htmlspecialchars($data['email']) ?> | <?= htmlspecialchars($data['phone']) ?>) — <?= $online_status ?></p>
  <p><strong>Category:</strong> <?= htmlspecialchars($data['category']) ?></p>
  <p><strong>Make/Model:</strong> <?= htmlspecialchars($data['make']) ?> / <?= htmlspecialchars($data['model']) ?></p>
  <p><strong>Serial:</strong> <?= htmlspecialchars($data['serial']) ?></p>
  <p><strong>Build Request:</strong> <?= $data['build'] === 'yes' ? 'Yes' : 'No' ?></p>
  <p><strong>Device Type:</strong> <?= htmlspecialchars($data['device_type']) ?></p>
  <p><strong>Issue:</strong><br><?= nl2br(htmlspecialchars($data['issue'])) ?></p>
  <p><strong>Submitted:</strong> <?= $data['created_at'] ?></p>

  <form method="post">
    <label>Status:</label>
    <select name="status">
      <?php
      $options = ['New', 'In Progress', 'Awaiting Customer', 'Completed'];
      foreach ($options as $opt) {
        $sel = ($data['status'] === $opt) ? 'selected' : '';
        echo "<option value=\"$opt\" $sel>$opt</option>";
      }
      ?>
    </select>

    <label>Internal Admin Notes:</label>
    <textarea name="admin_note"><?= htmlspecialchars($data['admin_note']) ?></textarea>

    <button type="submit">Save Changes</button>
  </form>
  <p><a href="index.php">← Back to All Requests</a></p>
</body>
</html>
