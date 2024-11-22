<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/services_controller.php");

$provider_id = $_SESSION['user_id'];
$service_name = $_POST['service_name'];
$price = $_POST['price'];

$result = add_service_ctr($provider_id, $service_name, $price);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Service added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add service']);
}
