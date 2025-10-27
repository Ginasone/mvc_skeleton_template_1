<?php
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';
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
    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    $user_id = get_user_id();
    
    // validate brand name
    $validation = validate_brand_name($brand_name);
    if (!$validation['valid']) {
        $response['status'] = 'error';
        $response['message'] = $validation['message'];
        echo json_encode($response);
        exit;
    }
    
    // validate category
    if ($cat_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Please select a valid category';
        echo json_encode($response);
        exit;
    }
    
    // check if category exists and belongs to user
    $category = get_category_by_id_ctr($cat_id, $user_id);
    if (!$category) {
        $response['status'] = 'error';
        $response['message'] = 'Category not found or access denied';
        echo json_encode($response);
        exit;
    }
    
    // check if brand already exists in this category
    if (brand_exists_ctr($brand_name, $cat_id, $user_id)) {
        $response['status'] = 'error';
        $response['message'] = 'Brand name already exists in this category';
        echo json_encode($response);
        exit;
    }
    
    // add brand
    $result = add_brand_ctr($brand_name, $cat_id, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Brand added successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add brand';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>