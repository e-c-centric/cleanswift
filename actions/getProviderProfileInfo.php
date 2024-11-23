<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/provider_controller.php");

$provider_id = $_SESSION['user_id'];

$provider = new provider_controller();
$result = $provider->get_provider_by_id($provider_id);

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get provider profile info']);
}
