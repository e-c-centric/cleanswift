<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/driver_controller.php");

$provider_id = $_SESSION['user_id'];

if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['contact']) || !isset($_POST['license_number'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$provider = new driver_controller();

$driver = $provider->update_driver($provider_id, $_POST['username'], $_POST['email'], $_POST['contact'], $_POST['license_number']);

if ($driver) {
    echo json_encode(['status' => 'success', 'message' => 'Driver profile updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update driver profile']);
}
