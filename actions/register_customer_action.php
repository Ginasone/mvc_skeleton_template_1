<?php
/**
 * Enhanced Debug Registration Action
 * This will identify the exact database connection issue
 */

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Tell the browser we're sending back JSON data
header('Content-Type: application/json');

// Start session
session_start();

$response = array();
$debug_info = array();

try {
    $debug_info[] = "=== ENHANCED DEBUG REGISTRATION ===";
    $debug_info[] = "Timestamp: " . date('Y-m-d H:i:s');
    
    // Check POST method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Not a POST request');
    }
    $debug_info[] = "✓ POST request confirmed";
    
    // Test database credentials first
    $debug_info[] = "=== TESTING DATABASE CREDENTIALS ===";
    $cred_path = '../settings/db_cred.php';
    if (!file_exists($cred_path)) {
        throw new Exception("Database credentials file not found at: $cred_path");
    }
    
    require_once $cred_path;
    $debug_info[] = "✓ Database credentials loaded";
    $debug_info[] = "SERVER: " . SERVER;
    $debug_info[] = "USERNAME: " . USERNAME;
    $debug_info[] = "DATABASE: " . DATABASE;
    
    // Test direct MySQL connection
    $debug_info[] = "=== TESTING DIRECT MYSQL CONNECTION ===";
    $direct_conn = new mysqli(SERVER, USERNAME, PASSWD, DATABASE);
    if ($direct_conn->connect_error) {
        $debug_info[] = "❌ Direct connection failed: " . $direct_conn->connect_error;
        throw new Exception("Database connection failed: " . $direct_conn->connect_error);
    }
    $debug_info[] = "✓ Direct MySQL connection successful";
    
    // Check if customer table exists
    $table_check = $direct_conn->query("SHOW TABLES LIKE 'customer'");
    if ($table_check->num_rows == 0) {
        $debug_info[] = "❌ Customer table does not exist";
        throw new Exception("Customer table not found in database");
    }
    $debug_info[] = "✓ Customer table exists";
    
    // Show table structure
    $structure = $direct_conn->query("DESCRIBE customer");
    $debug_info[] = "=== CUSTOMER TABLE STRUCTURE ===";
    while ($row = $structure->fetch_assoc()) {
        $debug_info[] = $row['Field'] . " (" . $row['Type'] . ")";
    }
    
    $direct_conn->close();
    
    // Test database class
    $debug_info[] = "=== TESTING DATABASE CLASS ===";
    $db_class_path = '../settings/db_class.php';
    if (!file_exists($db_class_path)) {
        throw new Exception("Database class not found at: $db_class_path");
    }
    
    require_once $db_class_path;
    $debug_info[] = "✓ Database class loaded";
    
    $db_test = new db_connection();
    if (!$db_test->db_connect()) {
        $debug_info[] = "❌ Database class connection failed";
        throw new Exception("Database class connection failed");
    }
    $debug_info[] = "✓ Database class connection successful";
    
    // Test a simple query
    $test_query = "SELECT COUNT(*) as count FROM customer";
    if (!$db_test->db_query($test_query)) {
        $debug_info[] = "❌ Database query test failed";
        throw new Exception("Database query execution failed");
    }
    $debug_info[] = "✓ Database query test successful";
    
    // Load controller
    $debug_info[] = "=== LOADING CONTROLLER ===";
    $controller_path = '../controllers/customer_controller.php';
    if (!file_exists($controller_path)) {
        throw new Exception("Controller not found at: $controller_path");
    }
    
    require_once $controller_path;
    $debug_info[] = "✓ Controller loaded";
    
    // Check POST data
    $debug_info[] = "=== POST DATA RECEIVED ===";
    foreach ($_POST as $key => $value) {
        $debug_info[] = "$key: " . (strlen($value) > 50 ? substr($value, 0, 50) . "..." : $value);
    }
    
    // Get form data
    $customer_name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email_address = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $user_password = isset($_POST['password']) ? $_POST['password'] : '';
    $customer_country = isset($_POST['country']) ? sanitize_input($_POST['country']) : '';
    $customer_city = isset($_POST['city']) ? sanitize_input($_POST['city']) : '';
    $phone_number = isset($_POST['contact']) ? sanitize_input($_POST['contact']) : '';
    $account_type = isset($_POST['role']) ? (int)$_POST['role'] : 2;
    
    $debug_info[] = "=== SANITIZED DATA ===";
    $debug_info[] = "Name: '$customer_name'";
    $debug_info[] = "Email: '$email_address'";
    $debug_info[] = "Password length: " . strlen($user_password);
    $debug_info[] = "Country: '$customer_country'";
    $debug_info[] = "City: '$customer_city'";
    $debug_info[] = "Phone: '$phone_number'";
    $debug_info[] = "Role: $account_type";
    
    // Skip validation for debug - go straight to registration attempt
    $debug_info[] = "=== ATTEMPTING REGISTRATION ===";
    
    // Test email check first
    $email_exists = check_email_exists_ctr($email_address);
    $debug_info[] = "Email exists check: " . ($email_exists ? "TRUE" : "FALSE");
    
    if ($email_exists) {
        $response['status'] = 'error';
        $response['message'] = 'Email already exists';
        $response['debug'] = $debug_info;
        echo json_encode($response);
        exit;
    }
    
    // Attempt registration
    $registration_result = register_customer_ctr($customer_name, $email_address, $user_password, $customer_country, $customer_city, $phone_number, $account_type);
    $debug_info[] = "Registration result: " . ($registration_result ? "SUCCESS" : "FAILED");
    
    if ($registration_result) {
        // Check if record was actually inserted
        $verify_insert = check_email_exists_ctr($email_address);
        $debug_info[] = "Verification check: " . ($verify_insert ? "RECORD FOUND" : "RECORD NOT FOUND");
        
        $response['status'] = 'success';
        $response['message'] = 'Registration successful!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Registration failed at database level';
    }
    
    $response['debug'] = $debug_info;

} catch (Exception $e) {
    $debug_info[] = "❌ EXCEPTION: " . $e->getMessage();
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    $response['debug'] = $debug_info;
}

echo json_encode($response);
exit;
?>