<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

session_start();

if (isset($_POST['provider_id'], $_POST['dropoff_time'])) {
    $customer_id = $_SESSION['user_id'];
    $provider_id = $_POST['provider_id'];
    $dropoff_time = $_POST['dropoff_time'];
    $pickup_time = isset($_POST['pickup_time']) ? $_POST['pickup_time'] : null;

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->request_delivery($customer_id, $provider_id, $dropoff_time, $pickup_time);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Delivery requested successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to request delivery.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters.']);
}
