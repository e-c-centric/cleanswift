<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

$delivery_controller = new delivery_controller();
$result = $delivery_controller->get_total_cost();

if ($result) {
    echo json_encode(['status' => 'success', 'total_cost' => $result['total_cost']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve total cost.']);
}
