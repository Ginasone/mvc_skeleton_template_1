<?php
header('Content-Type: application/json');
session_start();

$response = array();

try {
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request');
    }
    
    require_once '../controllers/customer_controller.php';
    
    // get form data
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $country = isset($_POST['country']) ? sanitize_input($_POST['country']) : '';
    $city = isset($_POST['city']) ? sanitize_input($_POST['city']) : '';
    $contact = isset($_POST['contact']) ? sanitize_input($_POST['contact']) : '';
    $role = isset($_POST['role']) ? (int)$_POST['role'] : 2;
    
    // check required fields
    $errors = array();
    
    if(empty($name)) {
        $errors[] = 'Name is required';
    }
    if(empty($email)) {
        $errors[] = 'Email is required';
    }
    if(empty($password)) {
        $errors[] = 'Password is required';
    }
    if(empty($country)) {
        $errors[] = 'Country is required';
    }
    if(empty($city)) {
        $errors[] = 'City is required';
    }
    if(empty($contact)) {
        $errors[] = 'Contact is required';
    }
    
    // validate data
    if(!validate_name($name)) {
        $errors[] = 'Invalid name format';
    }
    if(!validate_email($email)) {
        $errors[] = 'Invalid email format';
    }
    $pass_check = validate_password($password);
    if(!$pass_check['valid']) {
        $errors[] = $pass_check['message'];
    }
    if(!validate_location($country)) {
        $errors[] = 'Invalid country format';
    }
    if(!validate_location($city)) {
        $errors[] = 'Invalid city format';
    }
    if(!validate_phone($contact)) {
        $errors[] = 'Invalid phone number';
    }
    
    if(count($errors) > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Please fix the errors';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }
    
    // check if email exists
    if(check_email_exists_ctr($email)) {
        $response['status'] = 'error';
        $response['message'] = 'Email already exists';
        echo json_encode($response);
        exit;
    }
    
    // register customer
    $result = register_customer_ctr($name, $email, $password, $country, $city, $contact, $role);
    
    if($result) {
        $response['status'] = 'success';
        $response['message'] = 'Registration successful';
        $response['redirect'] = '../login/login.php';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Registration failed';
    }

} catch(Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>