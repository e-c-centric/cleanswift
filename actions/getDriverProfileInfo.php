<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/driver_controller.php");

$provider_id = $_SESSION['user_id'];

$provider = new driver_controller();
$result = $provider->get_profile_by_id($provider_id);

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get driver profile info']);
}