<?php
header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");
require_once("../controllers/order_controller.php");
require_once("../controllers/payment_controller.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

if (!isset($_REQUEST['delivery_id']) || !isset($_REQUEST['new_status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit();
}

$delivery_id = $_REQUEST['delivery_id'];
$new_status = $_REQUEST['new_status'];


if ($_SESSION['role_id'] == 1) {
    $result = ['status' => 'error', 'message' => 'Unauthorized access to customers.'];
    echo json_encode($result);
    exit();
}
$delivery = new delivery_controller();

switch ($new_status) {
    case 'Transit to laundry':
        if ($_SESSION['role_id'] != 2) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit();
        }
        $result = $delivery->pick_up_laundry($delivery_id);
        if ($result) {
            $result = ['status' => 'success', 'message' => 'Delivery status updated successfully.'];
        } else {
            $result = ['status' => 'error', 'message' => 'Failed to update delivery status.'];
        }
        break;

    case 'Transit from laundry':
        if ($_SESSION['role_id'] != 3) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit();
        }
        $result = $delivery->laundry_ready($delivery_id);
        if ($result) {
            $result = ['status' => 'success', 'message' => 'Delivery status updated successfully.'];
        } else {
            $result = ['status' => 'error', 'message' => 'Failed to update delivery status.'];
        }
        break;

    case 'Returned to customer':
        if ($_SESSION['role_id'] != 2) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit();
        }
        $result = $delivery->complete_delivery($delivery_id);
        if ($result) {
            $part2 = create_order_from_delivery_ctr($delivery_id);
            if ($part2) {
                $part3 = new PaymentController();
                $part3->add_payment_ctr($part2);
                $result = ['status' => 'success', 'message' => 'Delivery status updated successfully.'];
            } else {
                $result = ['status' => 'error', 'message' => 'Failed to update delivery status.'];
            }
        } else {
            $result = ['status' => 'error', 'message' => 'Failed to update delivery status.'];
        }
        break;

    default:
        $result = ['status' => 'error', 'message' => 'Invalid delivery status.'];
        break;
}

echo json_encode($result);
