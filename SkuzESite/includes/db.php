<?php
/**
 * Centralised database connection.
 *
 * Loads credentials from the application config and exposes an open
 * `mysqli` instance via `$conn` for the rest of the application to use.
 */

// Load configuration; `../config.php` is relative to this file.
$config = require __DIR__ . '/../config.php';

// Create the database connection using standard config keys.
$conn = new mysqli(
    $config['db_host'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_name']
);

// Abort immediately if the connection fails.
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

?>
