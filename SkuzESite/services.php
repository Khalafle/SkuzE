<?php
require 'includes/auth.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Choose a Service</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <a class="logo-link" href="index.php">
    <img src="assets/logo.png" alt="SkuzE Logo">
  </a>

  <h2>What would you like to do?</h2>
  <form method="get" action="service-step.php">
    <select name="category" required>
      <option value="">Select One</option>
      <option value="phone">Phone</option>
      <option value="console">Game Console</option>
      <option value="pc">PC</option>
      <option value="other">Other Device</option>
    </select>
    <button type="submit">Next</button>
  </form>
</body>
</html>
