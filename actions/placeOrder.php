<?php
// filepath: /c:/xampp/htdocs/cleanswift/actions/placeOrder.php
header('Content-Type: application/json');
require_once("../controllers/order_controller.php");

session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$customer_id = $_SESSION['user_id'];
$service_provider_ids = isset($_POST['provider_id']) ? $_POST['provider_id'] : [];

// Validate that provider_id is an array
if (!is_array($service_provider_ids) || empty($service_provider_ids)) {
    echo json_encode(['status' => 'error', 'message' => 'Service provider IDs are required and must be an array.']);
    exit();
}

// Place the orders
$placed_order_ids = place_orders_ctr($customer_id, $service_provider_ids);

if ($placed_order_ids) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Orders placed successfully.',
        'order_ids' => $placed_order_ids
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to place orders.']);
}
