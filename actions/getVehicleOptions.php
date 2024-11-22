<?php
session_start();
header('Content-Type: application/json');

require("../controllers/vehicle_options_controller.php");

$vehicles = new v_options_controller();
$result = $vehicles->get_vehicle_options();

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get vehicle options']);
}
