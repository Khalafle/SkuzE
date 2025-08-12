<?php
require '../includes/auth.php';
require '../includes/db.php';

if (!$_SESSION['is_admin']) {
  header("Location: ../dashboard.php");
  exit;
}

$stmt = $conn->query("SELECT r.id, u.username, r.category, r.issue, r.created_at, r.status 
                      FROM service_requests r 
                      JOIN users u ON r.user_id = u.id 
                      ORDER BY r.created_at DESC");
$requests = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <h2>Admin Panel</h2>
  <p><a href="../dashboard.php">Back to Dashboard</a></p>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Category</th>
        <th>Issue</th>
        <th>Status</th>
        <th>Date</th>
        <th>View</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $req): ?>
        <tr>
          <td><?= $req['id'] ?></td>
          <td><?= htmlspecialchars($req['username']) ?></td>
          <td><?= htmlspecialchars($req['category']) ?></td>
          <td><?= htmlspecialchars($req['issue']) ?></td>
          <td><?= htmlspecialchars($req['status'] ?? 'New') ?></td>
          <td><?= $req['created_at'] ?></td>
          <td><a href="view.php?id=<?= $req['id'] ?>">Open</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
