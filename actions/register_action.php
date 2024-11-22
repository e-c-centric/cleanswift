<?php
require_once("../controllers/customer_controller.php");
require_once("../controllers/driver_controller.php");
require_once("../controllers/provider_controller.php");
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];

    switch ($role) {
        case 'customer':
            $country = $_POST['country'];
            $city = $_POST['city'];
            $image = $_POST['image'] ?? null;
            $customerController = new customer_controller();
            $result = $customerController->add_customer($name, $email, $password, $contact, $country, $city, $image);
            break;
        case 'driver':
            $license_number = $_POST['license_number'];
            $vehicle_number = $_POST['vehicle_number'];
            $vehicle_type = $_POST['vehicle_type'];
            $vehicle_class = $_POST['vehicle_class'];
            $driverController = new driver_controller();
            $result = $driverController->add_driver($name, $email, $password, $contact, $license_number, $vehicle_number, $vehicle_type, $vehicle_class);
            break;
        case 'provider':
            $provider_name = $_POST['provider_name'];
            $provider_address = $_POST['provider_address'];
            $providerController = new provider_controller();
            $result = $providerController->add_provider($name, $email, $password, $contact, $provider_name, $provider_address);
            break;
        case 'admin':
            $adminController = new admin_controller();
            $result = $adminController->add_admin($name, $email, $password, $contact);
            break;
        default:
            $result = "Invalid role specified.";
            break;
    }

    if (strpos($result, 'successfully') !== false) {
        echo json_encode(['status' => 'success', 'message' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}