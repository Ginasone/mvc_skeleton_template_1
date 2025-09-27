<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

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
    
    // get user categories
    $categories = get_user_categories_ctr($user_id);
    
    if ($categories !== false) {
        $response['status'] = 'success';
        $response['data'] = $categories;
        $response['count'] = count($categories);
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