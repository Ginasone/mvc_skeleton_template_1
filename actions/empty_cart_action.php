<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';

header('Content-Type: application/json');

$response = array();

try {
    // Check if user is logged in
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Please login first';
        echo json_encode($response);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $customer_id = get_user_id();
    
    // Empty cart
    $result = empty_cart_ctr($customer_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Cart emptied successfully';
        $response['cart_count'] = 0;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to empty cart';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>