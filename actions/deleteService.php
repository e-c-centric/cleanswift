<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/services_controller.php");

$service_id = $_POST['service_id'];

$result = delete_service_ctr($service_id);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Service deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete service']);
}
