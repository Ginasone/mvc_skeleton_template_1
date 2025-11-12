<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';

header('Content-Type: application/json');

$response = array();

try {
    // Check if user is logged in
    if (!check_login()) {
        $response['status'] = 'success';
        $response['count'] = 0;
        echo json_encode($response);
        exit;
    }
    
    $customer_id = get_user_id();
    
    // Get cart count
    $count = get_cart_count_ctr($customer_id);
    
    $response['status'] = 'success';
    $response['count'] = $count;

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
    $response['count'] = 0;
}

echo json_encode($response);
exit;
?>