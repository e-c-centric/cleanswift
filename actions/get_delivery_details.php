<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$details = new delivery_controller();

if (isset($_REQUEST['delivery_id'])) {
    $delivery_id = $_REQUEST['delivery_id'];
    $delivery_details = $details->get_delivery_details($delivery_id);

    if ($delivery_details === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch delivery details.']);
        exit();
    }

    if (!empty($delivery_details)) {
        echo json_encode(['status' => 'success', 'data' => $delivery_details]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No delivery details found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Delivery ID not provided.']);
}