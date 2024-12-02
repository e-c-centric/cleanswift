<?php
// filepath: /c:/xampp/htdocs/cleanswift/actions/get_payments_by_provider.php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

header('Content-Type: application/json');

require_once("../controllers/payment_controller.php");

$paymentController = new PaymentController();

$provider_id = $_SESSION['user_id'];

try {
    $payments = $paymentController->get_payments_by_provider_ctr($provider_id);
    echo json_encode(['status' => 'success', 'data' => $payments]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
