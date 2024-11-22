<?php
session_start();
require_once("../controllers/user_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userController = new user_controller();
    $result = $userController->login($email, $password);

    if (is_array($result)) {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['name'] = $result['name'];
        $_SESSION['role_id'] = $result['role_id'];
        echo json_encode(['status' => 'success', 'message' => 'Login successful', 'data' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}