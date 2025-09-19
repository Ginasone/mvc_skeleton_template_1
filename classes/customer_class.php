<?php
require_once("../settings/db_class.php");

class customer_class extends db_connection
{
    // add new customer to database
    public function add_customer($name, $email, $password, $country, $city, $contact, $role = 2)
    {
        // hash password for security
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        
        // clean data to prevent sql injection
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $hashed_pass = mysqli_real_escape_string($this->db_conn(), $hashed_pass);
        $country = mysqli_real_escape_string($this->db_conn(), $country);
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        
        $sql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role) 
                VALUES ('$name', '$email', '$hashed_pass', '$country', '$city', '$contact', $role)";
        
        return $this->db_query($sql);
    }
    
    // check if email already exists
    public function email_exists($email)
    {
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $sql = "SELECT customer_id FROM customer WHERE customer_email = '$email'";
        $result = $this->db_fetch_one($sql);
        
        if($result) {
            return true;
        } else {
            return false;
        }
    }
    
    // get customer by email
    public function get_customer_by_email($email)
    {
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $sql = "SELECT * FROM customer WHERE customer_email = '$email'";
        return $this->db_fetch_one($sql);
    }
    
    // get customer by id
    public function get_customer_by_id($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM customer WHERE customer_id = $id";
        return $this->db_fetch_one($sql);
    }
    
    // get all customers
    public function get_all_customers()
    {
        $sql = "SELECT customer_id, customer_name, customer_email, customer_country, customer_city, customer_contact, user_role FROM customer ORDER BY customer_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // update customer info
    public function edit_customer($id, $name, $email, $country, $city, $contact)
    {
        $id = (int)$id;
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $country = mysqli_real_escape_string($this->db_conn(), $country);
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        
        $sql = "UPDATE customer SET customer_name='$name', customer_email='$email', customer_country='$country', customer_city='$city', customer_contact='$contact' WHERE customer_id=$id";
        
        return $this->db_query($sql);
    }
    
    // update password
    public function update_password($id, $new_password)
    {
        $id = (int)$id;
        $hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
        $hashed_pass = mysqli_real_escape_string($this->db_conn(), $hashed_pass);
        
        $sql = "UPDATE customer SET customer_pass='$hashed_pass' WHERE customer_id=$id";
        return $this->db_query($sql);
    }
    
    // delete customer
    public function delete_customer($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM customer WHERE customer_id=$id";
        return $this->db_query($sql);
    }
    
    // login verification
    public function verify_login($email, $password)
    {
        $customer = $this->get_customer_by_email($email);
        
        if($customer && password_verify($password, $customer['customer_pass'])) {
            // remove password from return data
            unset($customer['customer_pass']);
            return $customer;
        }
        
        return false;
    }
    
    // login function for authentication
    public function login_customer($email, $password)
    {
        $customer = $this->get_customer_by_email($email);
        
        if(!$customer) {
            return false;
        }
        
        if(password_verify($password, $customer['customer_pass'])) {
            unset($customer['customer_pass']);
            return $customer;
        }
        
        return false;
    }
}
?>