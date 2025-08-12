<?php
require 'includes/auth.php';
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('pk_live_51RjC8XL0dDqg6vO16qQ75R4HBfedJxO75HPP10boFERBBARlvLrMIazpcZpXEl17zIJGX81YTYoq4Al13PdsEpb1002nrKy5aQ');

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
