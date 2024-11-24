<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/services_controller.php");

if (isset($_REQUEST['provider_id'])) {
    $provider_id = $_REQUEST['provider_id'];
} else {
    $provider_id = $_SESSION['user_id'];
}

$result = get_services_by_provider_id_ctr($provider_id);

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No services found']);
}
