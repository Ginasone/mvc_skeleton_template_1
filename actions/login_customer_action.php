<?php
header('Content-Type: application/json');
session_start();

$response = array();

try {
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request');
    }
    
    require_once '../controllers/customer_controller.php';
    
    // get login data
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // check if fields are empty
    $errors = array();
    
    if(empty($email)) {
        $errors[] = 'Email is required';
    }
    if(empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if(count($errors) > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill all fields';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }
    
    // try to login
    $login_result = login_customer_ctr($email, $password);
    
    if($login_result) {
        // set session variables
        $_SESSION['customer_id'] = $login_result['customer_id'];
        $_SESSION['customer_name'] = $login_result['customer_name'];
        $_SESSION['customer_email'] = $login_result['customer_email'];
        $_SESSION['customer_country'] = $login_result['customer_country'];
        $_SESSION['customer_city'] = $login_result['customer_city'];
        $_SESSION['customer_contact'] = $login_result['customer_contact'];
        $_SESSION['user_role'] = $login_result['user_role'];
        $_SESSION['login_time'] = time();
        
        $response['status'] = 'success';
        $response['message'] = 'Login successful';
        $response['redirect'] = '../index.php';
        
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email or password';
    }

} catch(Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Login error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>