<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

// Check if required POST parameter is set
if (isset($_POST['delivery_id'])) {
    $delivery_id = $_POST['delivery_id'];

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->laundry_ready($delivery_id);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Delivery status updated to "Transit from laundry".']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update delivery status.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing delivery ID.']);
}
