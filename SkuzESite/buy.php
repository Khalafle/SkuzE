<?php
session_start();
require_once 'includes/db.php';

// Handle add-to-cart requests. The form posts back to this page with a
// product_id and optional quantity. Items are stored in the session under the
// `cart` key as an associative array of product_id => qty.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }

    // After adding to cart redirect to the cart page for feedback.
    header('Location: cart.php');
    exit;
}

// Fetch all products from the database to display to the user.
$products = $conn->query('SELECT * FROM products ORDER BY name');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<h1>Products</h1>
<p><a href="cart.php">View Cart</a></p>
<div class="products">
<?php while ($product = $products->fetch_assoc()): ?>
    <div class="product">
        <?php if (!empty($product['image'])): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width:150px;">
        <?php endif; ?>
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><?= htmlspecialchars($product['description']) ?></p>
        <p><strong>$<?= number_format($product['price'], 2) ?></strong></p>
        <form method="post">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="number" name="qty" value="1" min="1">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>
