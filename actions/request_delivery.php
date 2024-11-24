<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

session_start();

error_log(json_encode($_POST));

if (isset($_POST['provider_id'], $_POST['dropoff_time'], $_POST['vehicle_type'])) {
    if (!is_array($_POST['provider_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid provider IDs format.']);
        exit();
    }

    $customer_id = $_SESSION['user_id'];
    $provider_ids = $_POST['provider_id'];

    $dropoff_time = $_POST['dropoff_time'];
    $pickup_time = isset($_POST['pickup_time']) ? $_POST['pickup_time'] : null;
    $vehicle_type = $_POST['vehicle_type'];

    if (empty($provider_ids) || empty($dropoff_time) || empty($vehicle_type)) {
        echo json_encode(['status' => 'error', 'message' => 'One or more required parameters are empty.']);
        exit();
    }

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->request_delivery($customer_id, $provider_ids, $dropoff_time, $pickup_time, $vehicle_type);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Delivery requested successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to request delivery.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters.']);
}
