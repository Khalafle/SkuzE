<?php
require 'includes/auth.php';

$category = $_GET['category'] ?? '';
if (!$category) {
  header("Location: services.php");
  exit;
}

function label($text, $name, $type = 'text', $required = true) {
  echo "<label>$text</label>";
  echo "<input name=\"$name\" type=\"$type\" " . ($required ? "required" : "") . ">";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Service Details</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <a class="logo-link" href="index.php"><img src="assets/logo.png" alt="SkuzE Logo"></a>
  <h2>Describe Your <?= htmlspecialchars(ucfirst($category)) ?> Issue</h2>

  <form method="post" action="submit-request.php" enctype="multipart/form-data">
    <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">

    <?php if ($category === 'phone' || $category === 'console' || $category === 'pc'): ?>
      <?php label("Make", "make"); ?>
      <?php label("Model", "model"); ?>
      <?php label("IMEI / Serial Number", "serial", 'text', false); ?>
      <?php label("Describe the problem", "issue"); ?>
    <?php endif; ?>

    <?php if ($category === 'pc'): ?>
      <label>Is this a custom build request?</label>
      <select name="build">
        <option value="no">No</option>
        <option value="yes">Yes, I want a PC built</option>
      </select>
    <?php endif; ?>

    <?php if ($category === 'other'): ?>
      <?php label("Device Type", "device_type"); ?>
      <?php label("Problem Description", "issue"); ?>
    <?php endif; ?>

    <div id="drop-area" class="drop-area">
      <p>Drag & drop a photo here or</p>
      <button id="fileSelect" type="button">Choose a file</button>
      <input type="file" id="fileElem" name="photo" accept="image/*">
    </div>

    <button type="submit">Review and Submit</button>
  </form>

  <script>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('fileElem');
    const fileSelect = document.getElementById('fileSelect');

    fileSelect.addEventListener('click', () => fileInput.click());

    ['dragenter', 'dragover'].forEach(evt => {
      dropArea.addEventListener(evt, e => {
        e.preventDefault();
        dropArea.classList.add('dragover');
      });
    });

    ['dragleave', 'drop'].forEach(evt => {
      dropArea.addEventListener(evt, e => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
      });
    });

    dropArea.addEventListener('drop', e => {
      e.preventDefault();
      fileInput.files = e.dataTransfer.files;
    });
  </script>
</body>
</html>
