<?php
/**
 * Customer Login Action Script
 * Handles customer login authentication and session management
 */

// Set content type to JSON for API response
header('Content-Type: application/json');

// Enable CORS if needed (for development)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start session management
session_start();

// Initialize response array
$response = array();

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Include the customer controller
    require_once '../controllers/customer_controller.php';
    
    // Get and sanitize login data
    $email_address = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $user_password = isset($_POST['password']) ? $_POST['password'] : ''; // Don't sanitize password
    
    // Validation array to collect errors
    $login_errors = array();
    
    // Validate email address
    if (empty($email_address)) {
        $login_errors[] = 'Please enter your email address';
    } elseif (!validate_email($email_address)) {
        $login_errors[] = 'Please enter a valid email address';
    } elseif (strlen($email_address) > 50) {
        $login_errors[] = 'Email address is too long';
    }
    
    // Validate password
    if (empty($user_password)) {
        $login_errors[] = 'Please enter your password';
    }
    
    // If there are validation errors, return them
    if (!empty($login_errors)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fix the following issues:';
        $response['errors'] = $login_errors;
        echo json_encode($response);
        exit;
    }
    
    // Attempt to authenticate the customer
    $login_result = login_customer_ctr($email_address, $user_password);
    
    if ($login_result) {
        // Login successful! Set up session variables
        $_SESSION['customer_id'] = $login_result['customer_id'];
        $_SESSION['customer_name'] = $login_result['customer_name'];
        $_SESSION['customer_email'] = $login_result['customer_email'];
        $_SESSION['customer_country'] = $login_result['customer_country'];
        $_SESSION['customer_city'] = $login_result['customer_city'];
        $_SESSION['customer_contact'] = $login_result['customer_contact'];
        $_SESSION['user_role'] = $login_result['user_role'];
        $_SESSION['customer_image'] = $login_result['customer_image'] ?? null;
        $_SESSION['login_time'] = time(); // Track when they logged in
        
        $response['status'] = 'success';
        $response['message'] = 'Welcome back! You have been logged in successfully.';
        $response['redirect'] = '../index.php';
        $response['customer_name'] = $login_result['customer_name'];
        $response['user_role'] = $login_result['user_role'];
        
        // Log the successful login
        error_log("Customer logged in: {$email_address} at " . date('Y-m-d H:i:s'));
        
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email address or password. Please check your credentials and try again.';
        
        // Log failed login attempt for security
        error_log("Failed login attempt for: {$email_address} at " . date('Y-m-d H:i:s'));
    }

} catch (Exception $e) {
    // Handle any unexpected errors
    $response['status'] = 'error';
    $response['message'] = 'An unexpected error occurred. Please try again.';
    
    // Log the error for debugging
    error_log("Login error: " . $e->getMessage() . " at " . date('Y-m-d H:i:s'));
}

// Return JSON response
echo json_encode($response);
exit;
?>