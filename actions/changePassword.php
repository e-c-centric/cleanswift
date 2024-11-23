<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

if (!isset($_POST['old_password']) || !isset($_POST['new_password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

require("../controllers/user_controller.php");

$user_id = $_SESSION['user_id'];

$user = new user_controller();
$result = $user->change_password($user_id, $_POST['old_password'], $_POST['new_password']);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Password changed successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to change password']);
}