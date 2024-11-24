<?php
header('Content-Type: application/json');
require_once("../controllers/order_controller.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if (empty($order_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Order ID is required.']);
    exit();
}

$order_details = get_order_details_ctr($order_id);

if ($order_details === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch order details.']);
    exit();
}

if (!empty($order_details)) {
    echo json_encode(['status' => 'success', 'data' => $order_details]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No order details found.']);
}