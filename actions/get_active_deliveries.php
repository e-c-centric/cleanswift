<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$driver_id = $_SESSION['user_id'];

$delivery = new delivery_controller();

$deliveries = $delivery->get_deliveries_by_driver_id($driver_id);

if ($deliveries === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch deliveries.']);
    exit();
}

if (!empty($deliveries)) {
    echo json_encode(['status' => 'success', 'data' => $deliveries]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No deliveries found.']);
}
