<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit();
}

require_once("../controllers/customer_controller.php");

$customer_id = $_SESSION['user_id'];

$customer_controller = new customer_controller();

$customer_data = $customer_controller->get_customer_by_id($customer_id);

if (is_array($customer_data)) {
    echo json_encode(['status' => 'success', 'data' => $customer_data]);
} elseif (isset($customer_data['status']) && $customer_data['status'] === 'error') {
    echo json_encode(['status' => 'error', 'message' => $customer_data['message']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Unexpected error occurred.']);
}
