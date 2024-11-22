<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

// Check if required POST parameters are set
if (isset($_POST['delivery_id'], $_POST['driver_id'], $_POST['cost'])) {
    $delivery_id = $_POST['delivery_id'];
    $driver_id = $_POST['driver_id'];
    $cost = $_POST['cost'];

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->accept_delivery($delivery_id, $driver_id, $cost);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Delivery accepted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to accept delivery.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters.']);
}
