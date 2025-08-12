<?php
session_start();
// Ensure we always include the DB helper from this directory so scripts
// that include auth.php don't look for a separate db.php in their own
// locations.
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Optional: update last_active for online tracking
$conn->query("UPDATE users SET last_active = NOW() WHERE id = " . intval($_SESSION['user_id']));
?>
