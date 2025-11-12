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
    
    // Get data
    $cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    
    if ($cart_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid cart item';
        echo json_encode($response);
        exit;
    }
    
    if ($qty <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Quantity must be at least 1';
        echo json_encode($response);
        exit;
    }
    
    // Update quantity
    $result = update_cart_item_ctr($cart_id, $qty);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Cart updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update cart';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>