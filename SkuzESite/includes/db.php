<?php
// Basic database connection helper. Include this file anywhere a mysqli
// connection is needed and use the `$conn` variable.

// Load configuration. The config file is located one directory above this
// script so we traverse with `../`.
$config = require __DIR__ . '/../config.php';

// Trim any accidental whitespace on credential values so we don't end up
// with usernames or passwords that include stray spaces.
$dbHost = trim($config['db_host']);
$dbUser = trim($config['db_user']);
$dbPass = trim($config['db_pass']);
$dbName = trim($config['db_name']);

// Create the connection using credentials from the config file.
$conn = new mysqli(
    $dbHost,
    $dbUser,
    $dbPass,
    $dbName
);

// If something goes wrong stop execution so the error is visible.
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
