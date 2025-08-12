<?php
session_start();
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<h1>Thank you for your purchase!</h1>
<p>Your order was completed successfully.</p>
<p><a href="buy.php">Return to products</a></p>
</body>
</html>
