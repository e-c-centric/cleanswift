<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

// Check if required GET parameter is set
if (isset($_GET['driver_id'])) {
    $driver_id = $_GET['driver_id'];

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->get_total_cost_by_driver($driver_id);

    if ($result) {
        echo json_encode(['status' => 'success', 'driver_id' => $driver_id, 'total_cost' => $result['total_cost']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve total cost for driver.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing driver ID.']);
}
