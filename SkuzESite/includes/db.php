<?php
// Basic database connection helper. Include this file anywhere a mysqli
// connection is needed and use the `$conn` variable.

// Load configuration. The config file is located one directory above this
// script so we traverse with `../`.
$config = require __DIR__ . '/../config.php';

// Create the connection using credentials from the config file.
$conn = new mysqli(
    $config['db_host'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_name']
);

// If something goes wrong stop execution so the error is visible.
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
