<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/customer_controller.php");

$customer_id = $_SESSION['user_id'];
$name = isset($_POST['name']) ? $_POST['name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$contact = isset($_POST['contact']) ? $_POST['contact'] : null;
$country = isset($_POST['country']) ? $_POST['country'] : null;
$city = isset($_POST['city']) ? $_POST['city'] : null;
$image = isset($_FILES['image']) ? file_get_contents($_FILES['image']['tmp_name']) : null;

$customer = new customer_controller();

try {
    $result = $customer->update_customer($customer_id, $name, $email, $password, $contact, $country, $city, $image);
    echo json_encode(['status' => 'success', 'message' => $result]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update customer profile: ' . $e->getMessage()]);
}