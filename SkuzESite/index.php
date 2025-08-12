<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkuzE | Electronics Repair & Modding</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/style.css">
  <script src="assets/theme-toggle.js" defer></script>
</head>
<body>
  <button id="theme-toggle">Toggle Theme</button>
  <a href="index.php" class="logo-link">
    <img src="assets/logo.png" alt="SkuzE Logo">
  </a>

  <div class="hero">
    <pre>
  _____ _              ______
 / ____| |            |  ____|
| (___ | |_ ___  _ __ | |__ ___  ___ ___
 \___ \| __/ _ \| '_ \|  __/ _ \/ __/ __|
 ____) | || (_) | | | | | | (_) \__ \__ \
|_____/ \__\___/|_| |_|_|  \___/|___/___/

    </pre>
    <h2>Repair. Modding. Modern Support.</h2>
    <p>Get started below. Whether you're fixing, upgrading, or building — SkuzE has you covered.</p>

    <div class="cta-buttons">
      <a href="services.php">Services</a>
      <a href="buy.php">Buy</a>
      <a href="trade.php">Trade</a>
    </div>

    <div class="secondary-links">
      <a href="about.php">About</a>
      <a href="help.php">Help/FAQ</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
      <?php else: ?>
        <a href="login.php">Login / Register</a>
      <?php endif; ?>
    </div>
  </div>

  <footer>
    <p>&copy; <?= date('Y') ?> SkuzE. All rights reserved. | <a href="about.php">About</a></p>
  </footer>
</body>
</html>
