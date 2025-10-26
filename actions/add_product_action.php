<?php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
require_once '../controllers/category_controller.php';
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
    
    // get form data
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
    $product_title = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
    $product_price = isset($_POST['product_price']) ? trim($_POST['product_price']) : 0;
    $product_desc = isset($_POST['product_desc']) ? trim($_POST['product_desc']) : '';
    $product_image = isset($_POST['product_image']) ? trim($_POST['product_image']) : '';
    $product_keywords = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';
    $user_id = get_user_id();
    
    // validate category
    if ($cat_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Please select a valid category';
        echo json_encode($response);
        exit;
    }
    
    // validate brand
    if ($brand_id <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Please select a valid brand';
        echo json_encode($response);
        exit;
    }
    
    // validate product data
    $validation = validate_product_data($product_title, $product_price, $product_desc);
    if (!$validation['valid']) {
        $response['status'] = 'error';
        $response['message'] = $validation['message'];
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
    
    // check if brand exists and belongs to user
    $brand = get_brand_by_id_ctr($brand_id, $user_id);
    if (!$brand) {
        $response['status'] = 'error';
        $response['message'] = 'Brand not found or access denied';
        echo json_encode($response);
        exit;
    }
    
    // validate image path if provided
    if (!empty($product_image)) {
        // ensure image path is within uploads directory
        if (strpos($product_image, 'uploads/') !== 0) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid image path';
            echo json_encode($response);
            exit;
        }
    }
    
    // add product
    $result = add_product_ctr($cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Product added successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add product';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>