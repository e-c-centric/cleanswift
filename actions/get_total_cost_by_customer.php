<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");

// Check if required GET parameter is set
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    $delivery_controller = new delivery_controller();
    $result = $delivery_controller->get_total_cost_by_customer($customer_id);

    if ($result) {
        echo json_encode(['status' => 'success', 'customer_id' => $customer_id, 'total_cost' => $result['total_cost']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve total cost for customer.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing customer ID.']);
}
