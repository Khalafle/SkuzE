<?php
$config = require __DIR__ . '/config.php';

$conn = new mysqli(
  $config['localhost:3306'],
  $config['skuzqsas_Developer '],
  $config['1zTh1z4n0k4yPAssw02d?!'],
  $config['skuzqsas_MainDB']
);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>
