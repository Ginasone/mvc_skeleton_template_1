<?php
require_once '../settings/core.php';

header('Content-Type: application/json');

$response = array();

try {
    // check if user is logged in
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Please login first';
        echo json_encode($response);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // check if file was uploaded
    if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] === UPLOAD_ERR_NO_FILE) {
        $response['status'] = 'error';
        $response['message'] = 'No file uploaded';
        echo json_encode($response);
        exit;
    }
    
    $file = $_FILES['product_image'];
    
    // check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['status'] = 'error';
        $response['message'] = 'File upload error: ' . $file['error'];
        echo json_encode($response);
        exit;
    }
    
    // validate file type
    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed';
        echo json_encode($response);
        exit;
    }
    
    // validate file size (max 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB in bytes
    if ($file['size'] > $max_size) {
        $response['status'] = 'error';
        $response['message'] = 'File size too large. Maximum size is 5MB';
        echo json_encode($response);
        exit;
    }
    
    $user_id = get_user_id();
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    // create directory structure: uploads/u{user_id}/p{product_id}/
    $base_dir = '../uploads';
    $user_dir = $base_dir . '/u' . $user_id;
    $product_dir = $user_dir . '/p' . $product_id;
    
    // ensure uploads directory exists
    if (!is_dir($base_dir)) {
        $response['status'] = 'error';
        $response['message'] = 'Upload directory not found. Please contact administrator';
        echo json_encode($response);
        exit;
    }
    
    // create user directory if it doesn't exist
    if (!is_dir($user_dir)) {
        if (!mkdir($user_dir, 0755, true)) {
            $response['status'] = 'error';
            $response['message'] = 'Failed to create user directory';
            echo json_encode($response);
            exit;
        }
    }
    
    // create product directory if it doesn't exist
    if (!is_dir($product_dir)) {
        if (!mkdir($product_dir, 0755, true)) {
            $response['status'] = 'error';
            $response['message'] = 'Failed to create product directory';
            echo json_encode($response);
            exit;
        }
    }
    
    // verify that the resolved path is still inside uploads/
    $resolved_product_dir = realpath($product_dir);
    $resolved_base_dir = realpath($base_dir);
    
    if (strpos($resolved_product_dir, $resolved_base_dir) !== 0) {
        $response['status'] = 'error';
        $response['message'] = 'Security violation: Invalid upload path';
        echo json_encode($response);
        exit;
    }
    
    // generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'product_' . time() . '_' . uniqid() . '.' . $file_extension;
    $destination = $product_dir . '/' . $new_filename;
    
    // move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // store relative path from project root
        $relative_path = 'uploads/u' . $user_id . '/p' . $product_id . '/' . $new_filename;
        
        $response['status'] = 'success';
        $response['message'] = 'File uploaded successfully';
        $response['file_path'] = $relative_path;
        $response['file_name'] = $new_filename;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to move uploaded file';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>