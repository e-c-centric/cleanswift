<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/services_controller.php");

$service_id = $_POST['service_id'];
$service_name = $_POST['service_name'];
$price = $_POST['price'];

$result = update_service_ctr($service_id, $service_name, $price);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Service updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update service']);
}
