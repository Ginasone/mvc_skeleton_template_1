<?php
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

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
    
    // get brand id
    $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
    $user_id = get_user_id();
    
    if ($brand_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid brand ID';
        echo json_encode($response);
        exit;
    }
    
    // check if brand exists for this user
    $existing_brand = get_brand_by_id_ctr($brand_id, $user_id);
    if (!$existing_brand) {
        $response['status'] = 'error';
        $response['message'] = 'Brand not found or access denied';
        echo json_encode($response);
        exit;
    }
    
    // delete brand
    $result = delete_brand_ctr($brand_id, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Brand deleted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete brand';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>