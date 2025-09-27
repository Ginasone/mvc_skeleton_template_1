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
    
    // get form data
    $cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
    $user_id = get_user_id();
    
    // validate category name
    $validation = validate_category_name($cat_name);
    if (!$validation['valid']) {
        $response['status'] = 'error';
        $response['message'] = $validation['message'];
        echo json_encode($response);
        exit;
    }
    
    // check if category already exists
    if (category_exists_ctr($cat_name, $user_id)) {
        $response['status'] = 'error';
        $response['message'] = 'Category name already exists';
        echo json_encode($response);
        exit;
    }
    
    // add category
    $result = add_category_ctr($cat_name, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Category added successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add category';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>