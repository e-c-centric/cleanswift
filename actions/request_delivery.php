<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

session_start();

// Log the POST data for debugging
error_log(json_encode($_POST));

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit();
}

// Define required POST parameters
$required_params = ['provider_id', 'dropoff_time', 'vehicle_type'];

// Verify all required parameters are present
foreach ($required_params as $param) {
    if (!isset($_POST[$param])) {
        echo json_encode(['status' => 'error', 'message' => "Missing required parameter: {$param}."]);
        exit();
    }
}

// Extract and sanitize parameters
$provider_ids = $_POST['provider_id'];
$dropoff_time = trim($_POST['dropoff_time']);
$pickup_time = isset($_POST['pickup_time']) ? trim($_POST['pickup_time']) : null;
$vehicle_type = trim($_POST['vehicle_type']);

// Validate that provider_ids is an array
if (!is_array($provider_ids)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid provider IDs format.']);
    exit();
}

// Ensure required fields are not empty
if (empty($provider_ids) || empty($dropoff_time) || empty($vehicle_type)) {
    echo json_encode(['status' => 'error', 'message' => 'One or more required parameters are empty.']);
    exit();
}

// Assign customer ID from session
$customer_id = $_SESSION['user_id'];

// Instantiate the delivery controller
$delivery_controller = new delivery_controller();

// Request delivery
$result = $delivery_controller->request_delivery($customer_id, $provider_ids, $dropoff_time, $vehicle_type, $pickup_time);

error_log(json_encode($result));
// Respond based on the result
if ($result) {
    
    echo json_encode(['status' => 'success', 'message' => 'Delivery requested successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to request delivery.']);
}
