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
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // get category id
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    $user_id = get_user_id();
    
    if ($cat_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid category ID';
        echo json_encode($response);
        exit;
    }
    
    // check if category exists for this user
    $existing_category = get_category_by_id_ctr($cat_id, $user_id);
    if (!$existing_category) {
        $response['status'] = 'error';
        $response['message'] = 'Category not found or access denied';
        echo json_encode($response);
        exit;
    }
    
    // delete category
    $result = delete_category_ctr($cat_id, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Category deleted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete category';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>