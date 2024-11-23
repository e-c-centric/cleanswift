<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit();
}

require_once("../controllers/customer_controller.php");

$customer_id = $_SESSION['user_id'];
$name = $_POST['name'] ?? '';
$contact = $_POST['contact'] ?? '';
$country = $_POST['country'] ?? '';
$city = $_POST['city'] ?? '';

if (!empty($city)) {
    $curl = curl_init();

    $address = urlencode($city);

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://ghanapostgps.sperixlabs.org/get-location",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "address={$address}",
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
        echo json_encode(['status' => 'error', 'message' => 'Invalid city address provided.']);
        exit();
    }
}

if (empty($name) || empty($contact) || empty($country) || empty($city)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
    $fileName = $_FILES['profilePicture']['name'];
    $fileSize = $_FILES['profilePicture']['size'];
    $fileType = $_FILES['profilePicture']['type'];

    $allowedTypes = ['image/png'];
    $maxSize = 2 * 1024 * 1024;

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Only PNG files are allowed.']);
        exit();
    }

    if ($fileSize > $maxSize) {
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds the 2MB limit.']);
        exit();
    }

    $fileContent = file_get_contents($fileTmpPath);
    if ($fileContent === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to read the uploaded file.']);
        exit();
    }

    $encodedImage = base64_encode($fileContent);

    $customer = new customer_controller();
    $updateSuccess = $customer->update_customer($customer_id, $name, $contact, $country, $city, $encodedImage);

    if ($updateSuccess['status'] === "success") {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $updateSuccess['message']]);
    }
} else {
    $customer = new customer_controller();
    $updateSuccess = $customer->update_customer($customer_id, $name, $contact, $country, $city);

    if ($updateSuccess['status'] === "success") {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $updateSuccess['message']]);
    }
}
?>