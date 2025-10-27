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
    
    $user_id = get_user_id();
    
    // get user brands
    $brands = get_user_brands_ctr($user_id);
    
    if ($brands !== false) {
        $response['status'] = 'success';
        $response['data'] = $brands;
        $response['count'] = count($brands);
    } else {
        $response['status'] = 'success';
        $response['data'] = array();
        $response['count'] = 0;
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>