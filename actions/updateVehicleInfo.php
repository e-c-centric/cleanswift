<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/driver_controller.php");

$driver_id = $_SESSION['user_id'];

if (!isset($_POST['vehicle_number']) || !isset($_POST['vehicle_type']) || !isset($_POST['option_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$driver = new driver_controller();

$vehicle = $driver->update_vehicle($driver_id, $_POST['vehicle_number'], $_POST['vehicle_type'], $_POST['option_id']);

if ($vehicle) {
    echo json_encode(['status' => 'success', 'message' => 'Vehicle info updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update vehicle info']);
}
