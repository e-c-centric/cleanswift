<?php
// filepath: /c:/xampp/htdocs/cleanswift/actions/get_all_payments.php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

header('Content-Type: application/json');

require_once("../controllers/payment_controller.php");

$paymentController = new PaymentController();

try {
    $payments = $paymentController->get_all_payments_ctr();
    echo json_encode(['status' => 'success', 'data' => $payments]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
