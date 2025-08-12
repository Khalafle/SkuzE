<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkuzE | Electronics Repair & Modding</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/style.css">
  <script src="assets/theme-toggle.js" defer></script>
  <style>
    .hero {
      text-align: center;
      padding: 2rem 1rem;
    }
    .hero pre {
      font-family: monospace;
      font-size: 0.9rem;
      margin-bottom: 1rem;
      color: var(--fg);
    }
    .cta-buttons {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      max-width: 300px;
      margin: 0 auto;
    }
    .cta-buttons a {
      text-decoration: none;
      text-align: center;
      padding: 0.75rem;
      background: var(--accent);
      color: white;
      border-radius: 5px;
      transition: 0.2s;
    }
    .cta-buttons a:hover {
      opacity: 0.9;
    }
    footer {
      margin-top: 3rem;
      text-align: center;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
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
      <a href="login.php">Login / Sign Up</a>
      <a href="services.php">Start a Repair Request</a>
      <a href="about.php">Learn About SkuzE</a>
    </div>
  </div>

  <footer>
    <p>&copy; <?= date('Y') ?> SkuzE. All rights reserved. | <a href="about.php">About</a></p>
  </footer>
</body>
</html>
