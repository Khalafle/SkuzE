<?php
require 'includes/auth.php';

// Load Stripe secret from config file if present or fallback to environment variable
$stripeSecret = null;
$configPath = __DIR__ . '/config.php';
if (file_exists($configPath)) {
  $config = require $configPath;
  $stripeSecret = $config['stripe_secret'] ?? null;
} else {
  $stripeSecret = getenv('STRIPE_SECRET');
}

// Load Stripe dependencies if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
} else {
  die('Stripe dependencies missing.');
}

if (empty($stripeSecret)) {
  die('Stripe API key not configured.');
}

\Stripe\Stripe::setApiKey($stripeSecret);

$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => 'usd',
      'product_data' => ['name' => 'PC Build Reservation'],
      'unit_amount' => 5000, // $50.00
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'success.php',
  'cancel_url' => 'cancel.php',
]);

header("Location: " . $session->url);
exit;
