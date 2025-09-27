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
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    $cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
    $user_id = get_user_id();
    
    if ($cat_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid category ID';
        echo json_encode($response);
        exit;
    }
    
    // validate category name
    $validation = validate_category_name($cat_name);
    if (!$validation['valid']) {
        $response['status'] = 'error';
        $response['message'] = $validation['message'];
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
    
    // check if new name already exists (but not for this category)
    if (category_exists_ctr($cat_name, $user_id)) {
        // check if it's the same category
        if ($existing_category['cat_name'] != $cat_name) {
            $response['status'] = 'error';
            $response['message'] = 'Category name already exists';
            echo json_encode($response);
            exit;
        }
    }
    
    // update category
    $result = update_category_ctr($cat_id, $cat_name, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Category updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update category';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>