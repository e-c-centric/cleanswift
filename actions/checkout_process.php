<?php
// filepath: /c:/xampp/htdocs/cleanswift/actions/checkout_process.php
header("Content-Type: application/json");
session_start();

require_once("../controllers/payment_controller.php");
require_once("../controllers/order_controller.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  echo json_encode(["success" => false, "message" => "User not logged in."]);
  exit();
}

// Check if required POST parameters are set
if (!isset($_POST['payment_ref']) || !isset($_POST['payment_id'])) {
  echo json_encode(["success" => false, "message" => "Invalid request."]);
  exit();
}

$reference = $_POST['payment_ref'];
$payment_id = $_POST['payment_id'];
$amount = $_POST['amount'];

// Initialize cURL session for Paystack verification
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . urlencode($reference),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer sk_test_6e52520db4137d16c0e659544089ae791ff74285", // Replace with your Secret Key
    "Cache-Control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  // Log the error for debugging
  error_log("cURL Error #: " . $err);
  echo json_encode(["success" => false, "message" => "cURL Error #: " . $err]);
  exit();
} else {
  $response = json_decode($response, true);
  if (isset($response['status']) && $response['status'] === true && isset($response['data']['status']) && strtolower($response['data']['status']) === 'success') {
    // Update payment status in the database without verifying the amount
    $paymentController = new PaymentController();
    $updateStatus = $paymentController->update_payment_ctr($payment_id, $amount, "Paid");

    if ($updateStatus) {
      $order = delete_order_ctr($payment_id);
      echo json_encode(["success" => true, "message" => "Payment successful"]);
      exit();
    } else {
      echo json_encode(["success" => false, "message" => "Failed to update payment status"]);
      exit();
    }
  } else {
    // Log the response for debugging
    error_log(json_encode($response));
    $error_message = isset($response['message']) ? $response['message'] : 'Payment verification failed.';
    echo json_encode(["success" => false, "message" => "Payment verification failed: " . $error_message]);
    exit();
  }
}
