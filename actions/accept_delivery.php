<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

session_start();

if (isset($_POST['delivery_id'], $_POST['cost'])) {
    $delivery_id = $_POST['delivery_id'];
    $driver_id = $_SESSION['user_id'];
    $cost = $_POST['cost'];

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->accept_delivery($delivery_id, $driver_id, $cost);

    if ($result) {
        if ($result['status'] == 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Delivery accepted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to accept delivery.' . $result['message']]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters.']);
    }
}
