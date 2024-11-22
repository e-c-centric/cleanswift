<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/cart_controller.php");

$customer_id = $_SESSION['user_id'];

$result = clear_cart_ctr($customer_id);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Cart cleared']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to clear cart']);
}
