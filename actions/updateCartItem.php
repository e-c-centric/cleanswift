<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/cart_controller.php");

$customer_id = $_SESSION['user_id'];
$service_id = $_POST['service_id'];
$quantity = $_POST['quantity'];

$result = update_cart_item_ctr($customer_id, $service_id, $quantity);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Cart item updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update cart item']);
}
