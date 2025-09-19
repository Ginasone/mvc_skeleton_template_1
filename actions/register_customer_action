<?php
/**
 * Customer Registration Handler
 * This is where the magic happens when someone wants to join our platform!
 */

// Tell the browser we're sending back JSON data
header('Content-Type: application/json');

// Allow requests from anywhere (useful during development)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start up our session system
session_start();

// This will hold our response to send back to the user
$response = array();

try {
    // Make sure they're actually submitting a form to us
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Hey, you need to submit the form properly!');
    }
    
    // Bring in our customer management functions
    require_once '../controllers/customer_controller.php';
    
    // Grab all the information they sent us and clean it up
    $customer_name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email_address = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $user_password = isset($_POST['password']) ? $_POST['password'] : ''; // Don't clean the password
    $customer_country = isset($_POST['country']) ? sanitize_input($_POST['country']) : '';
    $customer_city = isset($_POST['city']) ? sanitize_input($_POST['city']) : '';
    $phone_number = isset($_POST['contact']) ? sanitize_input($_POST['contact']) : '';
    $account_type = isset($_POST['role']) ? (int)$_POST['role'] : 2; // Default to regular customer
    
    // Let's collect any problems we find
    $problems_found = array();
    
    // Check if their name looks good
    if (empty($customer_name)) {
        $problems_found[] = 'We need to know your name to create your account!';
    } elseif (!validate_name($customer_name)) {
        $problems_found[] = 'Your name should be between 2-100 characters and only contain letters and spaces.';
    }
    
    // Check their email address
    if (empty($email_address)) {
        $problems_found[] = 'We definitely need your email address!';
    } elseif (!validate_email($email_address)) {
        $problems_found[] = 'That email address doesn\'t look quite right. Could you double-check it?';
    } elseif (strlen($email_address) > 50) {
        $problems_found[] = 'That email address is too long for our system (maximum 50 characters).';
    }
    
    // Check their password
    if (empty($user_password)) {
        $problems_found[] = 'You\'ll need a password to keep your account secure!';
    } else {
        $password_check = validate_password($user_password);
        if (!$password_check['valid']) {
            $problems_found[] = $password_check['message'];
        }
    }
    
    // Check their country
    if (empty($customer_country)) {
        $problems_found[] = 'We need to know which country you\'re in!';
    } elseif (!validate_location($customer_country)) {
        $problems_found[] = 'Country name should be 2-30 characters with only letters and spaces.';
    }
    
    // Check their city
    if (empty($customer_city)) {
        $problems_found[] = 'What city are you located in?';
    } elseif (!validate_location($customer_city)) {
        $problems_found[] = 'City name should be 2-30 characters with only letters and spaces.';
    }
    
    // Check their phone number
    if (empty($phone_number)) {
        $problems_found[] = 'We need a phone number in case we need to reach you!';
    } elseif (!validate_phone($phone_number)) {
        $problems_found[] = 'That phone number doesn\'t look quite right. Make sure it has 10-15 digits.';
    } elseif (strlen($phone_number) > 15) {
        $problems_found[] = 'That phone number is too long for our system.';
    }
    
    // Make sure they picked a valid account type
    if (!in_array($account_type, [1, 2])) {
        $problems_found[] = 'Please choose whether you\'re a customer or restaurant owner.';
    }
    
    // If we found any problems, let them know what to fix
    if (!empty($problems_found)) {
        $response['status'] = 'error';
        $response['message'] = 'We found a few things that need fixing:';
        $response['errors'] = $problems_found;
        echo json_encode($response);
        exit;
    }
    
    // Check if someone already used this email address
    if (check_email_exists_ctr($email_address)) {
        $response['status'] = 'error';
        $response['message'] = 'Looks like someone already registered with this email address. Maybe try logging in instead?';
        echo json_encode($response);
        exit;
    }
    
    // Alright, everything looks good! Let's create their account
    $registration_successful = register_customer_ctr($customer_name, $email_address, $user_password, $customer_country, $customer_city, $phone_number, $account_type);
    
    if ($registration_successful) {
        $response['status'] = 'success';
        $response['message'] = 'Welcome aboard! Your account has been created successfully. You can now log in and start exploring!';
        $response['redirect'] = '../login/login.php';
        
        // Keep a record of this happy moment
        error_log("New customer joined us: $email_address at " . date('Y-m-d H:i:s'));
        
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Oops! Something went wrong on our end. Could you try again in a moment?';
    }

} catch (Exception $problem) {
    // Something unexpected happened
    $response['status'] = 'error';
    $response['message'] = 'Something unexpected happened. Our technical team will look into this!';
    
    // Log what went wrong so we can fix it
    error_log("Registration system error: " . $problem->getMessage() . " at " . date('Y-m-d H:i:s'));
}

// Send the response back to the user
echo json_encode($response);
exit;
?>
