<?php
// Let's connect to our database
require("../settings/db_class.php");

/**
 * Customer Class - This handles everything related to our users
 * Think of this as the bridge between our website and the customer data
 */
class customer_class extends db_connection
{
    // === Creating New Customers ===
    
    /**
     * Welcome a new customer to our platform!
     * This function safely stores their information and creates their account
     */
    public function add_customer($full_name, $email_address, $user_password, $country, $city, $phone_number, $account_type = 2)
    {
        // First, let's make their password secure
        $secure_password = password_hash($user_password, PASSWORD_DEFAULT);
        
        // Clean up the data to keep our database safe from nasty stuff
        $clean_name = mysqli_real_escape_string($this->db_conn(), $full_name);
        $clean_email = mysqli_real_escape_string($this->db_conn(), $email_address);
        $clean_password = mysqli_real_escape_string($this->db_conn(), $secure_password);
        $clean_country = mysqli_real_escape_string($this->db_conn(), $country);
        $clean_city = mysqli_real_escape_string($this->db_conn(), $city);
        $clean_phone = mysqli_real_escape_string($this->db_conn(), $phone_number);
        $user_role = (int)$account_type; // Make sure this is a number
        
        // Now let's save them to our database
        $save_customer_query = "INSERT INTO `customer`(`customer_name`, `customer_email`, `customer_pass`, `customer_country`, `customer_city`, `customer_contact`, `user_role`) 
                VALUES ('$clean_name', '$clean_email', '$clean_password', '$clean_country', '$clean_city', '$clean_phone', $user_role)";
        
        return $this->db_query($save_customer_query);
    }
    
    // === Checking and Finding Customers ===
    
    /**
     * Is this email already taken?
     * We want to make sure each customer has a unique email
     */
    public function email_exists($email_to_check)
    {
        $safe_email = mysqli_real_escape_string($this->db_conn(), $email_to_check);
        $check_query = "SELECT customer_id FROM customer WHERE customer_email = '$safe_email'";
        $existing_customer = $this->db_fetch_one($check_query);
        
        return $existing_customer ? true : false; // Return true if email exists
    }
    
    /**
     * Find a customer by their email address
     * This is useful when they're trying to log in
     */
    public function get_customer_by_email($email_address)
    {
        $safe_email = mysqli_real_escape_string($this->db_conn(), $email_address);
        $search_query = "SELECT * FROM customer WHERE customer_email = '$safe_email'";
        return $this->db_fetch_one($search_query);
    }
    
    /**
     * Find a customer by their ID number
     * Each customer gets a unique ID when they register
     */
    public function get_customer_by_id($customer_id)
    {
        $safe_id = (int)$customer_id;
        $search_query = "SELECT * FROM customer WHERE customer_id = $safe_id";
        return $this->db_fetch_one($search_query);
    }
    
    /**
     * Get all our wonderful customers
     * (But keep their passwords private!)
     */
    public function get_all_customers()
    {
        $get_all_query = "SELECT customer_id, customer_name, customer_email, customer_country, customer_city, customer_contact, user_role 
                          FROM customer 
                          ORDER BY customer_id DESC";
        return $this->db_fetch_all($get_all_query);
    }
    
    // === Updating Customer Information ===
    
    /**
     * Let customers update their profile information
     * Sometimes people move or change their details
     */
    public function edit_customer($customer_id, $new_name, $new_email, $new_country, $new_city, $new_phone)
    {
        $safe_id = (int)$customer_id;
        $clean_name = mysqli_real_escape_string($this->db_conn(), $new_name);
        $clean_email = mysqli_real_escape_string($this->db_conn(), $new_email);
        $clean_country = mysqli_real_escape_string($this->db_conn(), $new_country);
        $clean_city = mysqli_real_escape_string($this->db_conn(), $new_city);
        $clean_phone = mysqli_real_escape_string($this->db_conn(), $new_phone);
        
        $update_query = "UPDATE customer SET 
                customer_name='$clean_name', 
                customer_email='$clean_email', 
                customer_country='$clean_country', 
                customer_city='$clean_city', 
                customer_contact='$clean_phone' 
                WHERE customer_id=$safe_id";
        
        return $this->db_query($update_query);
    }
    
    /**
     * Help customers change their password
     * Maybe they forgot it or want something more secure
     */
    public function update_password($customer_id, $new_password)
    {
        $safe_id = (int)$customer_id;
        $secure_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $clean_password = mysqli_real_escape_string($this->db_conn(), $secure_new_password);
        
        $password_update_query = "UPDATE customer SET customer_pass='$clean_password' WHERE customer_id=$safe_id";
        return $this->db_query($password_update_query);
    }
    
    /**
     * Let customers add a profile picture
     * Everyone likes to have a face with their account
     */
    public function update_customer_image($customer_id, $picture_path)
    {
        $safe_id = (int)$customer_id;
        $clean_path = mysqli_real_escape_string($this->db_conn(), $picture_path);
        
        $picture_update_query = "UPDATE customer SET customer_image='$clean_path' WHERE customer_id=$safe_id";
        return $this->db_query($picture_update_query);
    }
    
    // === Removing Customers ===
    
    /**
     * Sometimes we need to remove a customer account
     * This should be used carefully!
     */
    public function delete_customer($customer_id)
    {
        $safe_id = (int)$customer_id;
        $delete_query = "DELETE FROM customer WHERE customer_id=$safe_id";
        return $this->db_query($delete_query);
    }
    
    // === Login and Authentication ===
    
    /**
     * Check if a customer's login details are correct
     * This is like checking their ID at the door
     */
    public function verify_login($email_address, $entered_password)
    {
        $customer_info = $this->get_customer_by_email($email_address);
        
        if ($customer_info && password_verify($entered_password, $customer_info['customer_pass'])) {
            // Login is good! But let's not send back the password for security
            unset($customer_info['customer_pass']);
            return $customer_info;
        }
        
        return false; // Sorry, wrong details
    }
}
?>
