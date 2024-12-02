<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");
require_once("proxy.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$vehicle_class = $_SESSION['vehicle_class'];

$delivery = new delivery_controller();

$deliveries = $delivery->get_deliveries($vehicle_class);

if ($deliveries === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch deliveries.']);
    exit();
}

if (!empty($deliveries)) {
    foreach ($deliveries as $key => $value) {
        $pickup_location = $value['customer_city'];
        $locationResponse = getLocation($pickup_location);

        error_log(json_encode($locationResponse));

        // Initialize city as null
        $city = null;

        // Check if the response is an array and has the expected structure
        if (is_array($locationResponse)) {
            if (isset($locationResponse['found']) && $locationResponse['found']) {
                if (isset($locationResponse['data']['Table'][0]['District'])) {
                    $city = $locationResponse['data']['Table'][0]['District'];
                } else {
                    // Handle the case where 'Area' key is missing
                    $city = 'Area not found';
                }
            } elseif (isset($locationResponse['status']) && $locationResponse['status'] === 'error') {
                // Handle error returned from getLocation
                $city = 'Error: ' . $locationResponse['message'];
            }
        }

        // Assign the city value, whether found or error message
        $deliveries[$key]['city'] = $city;
    }
    echo json_encode(['status' => 'success', 'data' => $deliveries]);
} else {
    echo json_encode(['status' => 'success', 'data' => []]);
}
