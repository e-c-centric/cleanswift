<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

// Check if required POST parameter is set
if (isset($_POST['delivery_id'])) {
    $delivery_id = $_POST['delivery_id'];

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->complete_delivery($delivery_id);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Delivery completed and status updated to "Returned to customer".']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to complete delivery.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing delivery ID.']);
}
