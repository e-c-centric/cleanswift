<?php
header('Content-Type: application/json');
require_once("../controllers/order_controller.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$customer_id = $_SESSION['user_id'];

$orders = get_orders_by_customer_ctr($customer_id);

if ($orders === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch orders.']);
    exit();
}

if (!empty($orders)) {
    echo json_encode(['status' => 'success', 'data' => $orders]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No orders found.']);
}
