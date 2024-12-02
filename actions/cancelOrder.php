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

$role = $_SESSION['role_id'];

if ($role != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$order_id = $_POST['order_id'];

// Fulfill the order
$fulfilled_order = cancel_order_ctr($order_id);

if ($fulfilled_order) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Order fulfilled successfully.',
        'order_id' => $order_id
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fulfill order.']);
}
