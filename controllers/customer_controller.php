<?php
require_once("../classes/customer_class.php");

// register new customer
function register_customer_ctr($name, $email, $password, $country, $city, $contact, $role = 2)
{
    $customer = new customer_class();
    return $customer->add_customer($name, $email, $password, $country, $city, $contact, $role);
}

// check if email exists
function check_email_exists_ctr($email)
{
    $customer = new customer_class();
    return $customer->email_exists($email);
}

// get customer by email
function get_customer_by_email_ctr($email)
{
    $customer = new customer_class();
    return $customer->get_customer_by_email($email);
}

// get customer by id
function get_customer_by_id_ctr($id)
{
    $customer = new customer_class();
    return $customer->get_customer_by_id($id);
}

// get all customers
function get_all_customers_ctr()
{
    $customer = new customer_class();
    return $customer->get_all_customers();
}

// update customer
function edit_customer_ctr($id, $name, $email, $country, $city, $contact)
{
    $customer = new customer_class();
    return $customer->edit_customer($id, $name, $email, $country, $city, $contact);
}

// update password
function update_password_ctr($id, $new_password)
{
    $customer = new customer_class();
    return $customer->update_password($id, $new_password);
}

// delete customer
function delete_customer_ctr($id)
{
    $customer = new customer_class();
    return $customer->delete_customer($id);
}

// login customer
function login_customer_ctr($email, $password)
{
    $customer = new customer_class();
    return $customer->login_customer($email, $password);
}

// clean input data
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// validate email
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// validate phone number
function validate_phone($phone)
{
    // just check if it has some numbers and is reasonable length
    $clean_phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($clean_phone) >= 8 && strlen($clean_phone) <= 15;
}

// validate password
function validate_password($password)
{
    $result = array('valid' => true, 'message' => '');
    
    if(strlen($password) < 6) {
        $result['valid'] = false;
        $result['message'] = 'Password must be at least 6 characters';
    }
    
    return $result;
}

// validate name
function validate_name($name)
{
    // allow letters, spaces, hyphens, apostrophes, dots
    return preg_match('/^[a-zA-Z\s\-\'\.\,]{2,100}$/', $name) && strlen($name) >= 2 && strlen($name) <= 100;
}

// validate location
function validate_location($location)
{
    // allow letters, spaces, hyphens, dots
    return preg_match('/^[a-zA-Z\s\-\.\,]{2,30}$/', $location) && strlen($location) >= 2 && strlen($location) <= 30;
}
?>