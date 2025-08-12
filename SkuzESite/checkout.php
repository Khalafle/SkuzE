<?php
// Checkout builds a Stripe session based on the contents of the user's cart
// and stores a record of the order in the database.
require 'includes/auth.php'; // ensures the user is logged in and provides $conn
require_once 'vendor/autoload.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: buy.php');
    exit;
}

// Look up the product information for the items in the cart
$ids = array_map('intval', array_keys($cart));
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));
$stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$line_items = [];
$order_items = [];
$total = 0.0;
while ($row = $result->fetch_assoc()) {
    $qty = $cart[$row['id']];
    $line_items[] = [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => ['name' => $row['name']],
            'unit_amount' => (int)($row['price'] * 100),
        ],
        'quantity' => $qty,
    ];
    $order_items[] = ['id' => $row['id'], 'name' => $row['name'], 'price' => $row['price'], 'quantity' => $qty];
    $total += $row['price'] * $qty;
}
$stmt->close();

// Configure Stripe with a placeholder secret key. Replace with your actual key
// in a real deployment.
\Stripe\Stripe::setApiKey('sk_test_replace_with_real_key');

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $line_items,
    'mode' => 'payment',
    'success_url' => 'success.php',
    'cancel_url' => 'cancel.php',
]);

// Store order information tied to the Stripe session id
$stmt = $conn->prepare('INSERT INTO orders (session_id, items, total) VALUES (?, ?, ?)');
$json = json_encode($order_items);
$stmt->bind_param('ssd', $session->id, $json, $total);
$stmt->execute();
$stmt->close();

header('Location: ' . $session->url);
exit;
