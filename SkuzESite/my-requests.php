<?php
require 'includes/auth.php';
require 'includes/db.php';

$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, category, make, model, status, created_at FROM service_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Service Requests</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>My Requests</h2>
  <p><a href="dashboard.php">← Back to Dashboard</a></p>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Category</th>
        <th>Make/Model</th>
        <th>Status</th>
        <th>Submitted</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $r): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['category']) ?></td>
        <td><?= htmlspecialchars($r['make']) . ' / ' . htmlspecialchars($r['model']) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
        <td><?= $r['created_at'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
