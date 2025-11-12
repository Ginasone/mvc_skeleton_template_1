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
    
    // Get cart ID
    $cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
    $customer_id = get_user_id();
    
    if ($cart_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid cart item';
        echo json_encode($response);
        exit;
    }
    
    // Remove from cart
    /** @var bool $result */
    $result = remove_from_cart_ctr($cart_id);
    
    if ($result) {
        // Get updated cart count
        $cart_count = get_cart_count_ctr($customer_id);
        
        $response['status'] = 'success';
        $response['message'] = 'Item removed from cart';
        $response['cart_count'] = $cart_count;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to remove item from cart';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>