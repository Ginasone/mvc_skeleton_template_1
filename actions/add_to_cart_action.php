<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';

header('Content-Type: application/json');

$response = array();

try {
    // Check if user is logged in
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Please login to add items to cart';
        echo json_encode($response);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Get form data
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    $customer_id = get_user_id();
    
    // Validate inputs
    if ($product_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid product';
        echo json_encode($response);
        exit;
    }
    
    if ($qty <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Quantity must be at least 1';
        echo json_encode($response);
        exit;
    }
    
    // Add to cart (controller handles checking if exists)
    $result = add_to_cart_ctr($product_id, $customer_id, $qty);
    
    if ($result) {
        // Get updated cart count
        $cart_count = get_cart_count_ctr($customer_id);
        
        $response['status'] = 'success';
        $response['message'] = 'Product added to cart successfully';
        $response['cart_count'] = $cart_count;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add product to cart';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>