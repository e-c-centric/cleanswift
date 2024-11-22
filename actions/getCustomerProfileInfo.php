<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/customer_controller.php");

$customer_id = $_SESSION['user_id'];

$customer = new customer_controller();
$result = $customer->get_customer_by_id($customer_id);

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get customer profile info']);
}
