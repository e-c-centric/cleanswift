<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit();
}

require_once("../controllers/provider_controller.php");

$user_id = $_SESSION['user_id'];

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
$provider_name = isset($_POST['provider_name']) ? trim($_POST['provider_name']) : '';
$provider_address = isset($_POST['provider_address']) ? trim($_POST['provider_address']) : '';

if (empty($name) || empty($contact) || empty($provider_name) || empty($provider_address)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

if (!empty($provider_address)) {
    $curl = curl_init();

    $address = urlencode($provider_address);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ghanapostgps.sperixlabs.org/get-location",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "address={$provider_address}",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo json_encode(['status' => 'error', 'message' => 'Address verification failed: ' . curl_error($curl)]);
        curl_close($curl);
        exit();
    }

    curl_close($curl);

    $decodedResponse = json_decode($response, true);

    if (isset($decodedResponse['found']) && $decodedResponse['found'] === true) {
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid address provided.']);
        exit();
    }
}

try {
    $providerController = new provider_controller();

    $result = $providerController->update_provider($user_id, $name, $contact, $provider_name, $provider_address);

    if ($result === "Provider updated successfully.") {
        echo json_encode(['status' => 'success', 'message' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
