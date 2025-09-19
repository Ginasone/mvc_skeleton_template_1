<?php
// Let's bring in our customer functions
require_once("../classes/customer_class.php");

/**
 * Customer Controller - The middleman between our website and customer data
 * Think of this as a helpful assistant that translates what users want into database actions
 */

// === Helping New Customers Join Us ===

/**
 * Sign up a new customer to our platform
 * This is like helping someone fill out a membership form
 */
function register_customer_ctr($full_name, $email_address, $password, $country, $city, $phone_number, $account_type = 2)
{
    $customer_helper = new customer_class();
    return $customer_helper->add_customer($full_name, $email_address, $password, $country, $city, $phone_number, $account_type);
}

// === Looking Up Customer Information ===

/**
 * Check if someone already used this email
 * We want everyone to have their own unique email address
 */
function check_email_exists_ctr($email_to_check)
{
    $customer_finder = new customer_class();
    return $customer_finder->email_exists($email_to_check);
}

/**
 * Find a customer using their email address
 * Like looking someone up in a phone book
 */
function get_customer_by_email_ctr($email_address)
{
    $customer_searcher = new customer_class();
    return $customer_searcher->get_customer_by_email($email_address);
}

/**
 * Find a customer using their ID number
 * Each customer has a unique number when they join
 */
function get_customer_by_id_ctr($customer_id)
{
    $customer_searcher = new customer_class();
    return $customer_searcher->get_customer_by_id($customer_id);
}

/**
 * Get a list of all our customers
 * Useful for admin pages and customer management
 */
function get_all_customers_ctr()
{
    $customer_manager = new customer_class();
    return $customer_manager->get_all_customers();
}

// === Updating Customer Profiles ===

/**
 * Help customers update their information
 * People move, change phone numbers, etc.
 */
function edit_customer_ctr($customer_id, $new_name, $new_email, $new_country, $new_city, $new_phone)
{
    $profile_updater = new customer_class();
    return $profile_updater->edit_customer($customer_id, $new_name, $new_email, $new_country, $new_city, $new_phone);
}

/**
 * Help customers change their password
 * Sometimes they want something more secure or forgot their old one
 */
function update_password_ctr($customer_id, $new_password)
{
    $password_changer = new customer_class();
    return $password_changer->update_password($customer_id, $new_password);
}

/**
 * Let customers add a profile picture
 * Makes their account more personal
 */
function update_customer_image_ctr($customer_id, $picture_path)
{
    $picture_manager = new customer_class();
    return $picture_manager->update_customer_image($customer_id, $picture_path);
}

// === Account Management ===

/**
 * Remove a customer account (use carefully!)
 * Sometimes accounts need to be deleted
 */
function delete_customer_ctr($customer_id)
{
    $account_manager = new customer_class();
    return $account_manager->delete_customer($customer_id);
}

/**
 * Check if login details are correct
 * Like checking someone's ID at the door
 */
function verify_login_ctr($email_address, $password)
{
    $login_checker = new customer_class();
    return $login_checker->verify_login($email_address, $password);
}

/**
 * Handle customer login process
 * This manages the complete login flow
 */
function login_customer_ctr($email_address, $password)
{
    $customer_authenticator = new customer_class();
    return $customer_authenticator->login_customer($email_address, $password);
}

// === Helper Functions for Data Safety ===

/**
 * Clean up user input to keep our database safe
 * Removes dangerous characters and extra spaces
 */
function sanitize_input($dirty_data)
{
    $cleaned_data = trim($dirty_data); // Remove extra spaces
    $cleaned_data = stripslashes($cleaned_data); // Remove backslashes
    $cleaned_data = htmlspecialchars($cleaned_data); // Make it safe for web display
    return $cleaned_data;
}

/**
 * Check if an email address looks real
 * We want to make sure it has @ and a domain name
 */
function validate_email($email_address)
{
    return filter_var($email_address, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Check if a phone number looks reasonable
 * Basic check to see if it has enough digits
 */
function validate_phone($phone_number)
{
    // Remove everything except numbers and + sign
    $clean_phone = preg_replace('/[^0-9+]/', '', $phone_number);
    // Make sure it has between 10-15 digits (international standard)
    return preg_match('/^[\+]?[0-9]{10,15}$/', $clean_phone);
}

/**
 * Make sure the password is strong enough
 * We want our customers to be secure
 */
function validate_password($password)
{
    $feedback = ['valid' => true, 'message' => ''];
    
    if (strlen($password) < 6) {
        $feedback['valid'] = false;
        $feedback['message'] = 'Your password needs to be at least 6 characters long. Think of something memorable but secure!';
    }
    
    // Could add more checks here like:
    // - Has numbers and letters
    // - Has special characters
    // - Isn't too common (like "password123")
    
    return $feedback;
}

/**
 * Check if a name looks reasonable
 * Names should have letters and be the right length
 */
function validate_name($name)
{
    // Names should be 2-100 characters and only contain letters and spaces
    return preg_match('/^[a-zA-Z\s]{2,100}$/', $name);
}

/**
 * Make sure the location names look good
 * Countries and cities should only have letters
 */
function validate_location($location_name)
{
    // Should be 2-30 characters and only letters and spaces
    return preg_match('/^[a-zA-Z\s]{2,30}$/', $location_name);
}
?>