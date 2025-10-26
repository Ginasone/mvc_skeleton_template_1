<?php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

$response = array();

try {
    // check if user is logged in and is admin
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Please login first';
        echo json_encode($response);
        exit;
    }
    
    if (!check_admin()) {
        $response['status'] = 'error';
        $response['message'] = 'Admin privileges required';
        echo json_encode($response);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // get product id
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $user_id = get_user_id();
    
    if ($product_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid product ID';
        echo json_encode($response);
        exit;
    }
    
    // check if product exists for this user
    $existing_product = get_product_by_id_ctr($product_id, $user_id);
    if (!$existing_product) {
        $response['status'] = 'error';
        $response['message'] = 'Product not found or access denied';
        echo json_encode($response);
        exit;
    }
    
    // delete product
    $result = delete_product_ctr($product_id, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Product deleted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete product';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>