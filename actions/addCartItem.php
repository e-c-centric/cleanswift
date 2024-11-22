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

$result = add_to_cart_ctr($customer_id, $service_id, $quantity);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add item to cart']);
}
