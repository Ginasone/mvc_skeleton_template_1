<?php
require_once("../settings/db_class.php");

class brand_class extends db_connection
{
    // add new brand
    public function add_brand($brand_name, $cat_id, $user_id)
    {
        // clean data
        $brand_name = mysqli_real_escape_string($this->db_conn(), $brand_name);
        $cat_id = (int)$cat_id;
        $user_id = (int)$user_id;
        
        $sql = "INSERT INTO brands (brand_name, cat_id, created_by) VALUES ('$brand_name', $cat_id, $user_id)";
        return $this->db_query($sql);
    }
    
    // check if brand name exists for specific category and user
    public function brand_exists($brand_name, $cat_id, $user_id)
    {
        $brand_name = mysqli_real_escape_string($this->db_conn(), $brand_name);
        $cat_id = (int)$cat_id;
        $user_id = (int)$user_id;
        
        $sql = "SELECT brand_id FROM brands WHERE brand_name = '$brand_name' AND cat_id = $cat_id AND created_by = $user_id";
        $result = $this->db_fetch_one($sql);
        
        return $result ? true : false;
    }
    
    // get all brands for a user
    public function get_user_brands($user_id)
    {
        $user_id = (int)$user_id;
        $sql = "SELECT b.*, c.cat_name 
                FROM brands b 
                LEFT JOIN categories c ON b.cat_id = c.cat_id 
                WHERE b.created_by = $user_id 
                ORDER BY c.cat_name, b.brand_name ASC";
        return $this->db_fetch_all($sql);
    }
    
    // get brands by category
    public function get_brands_by_category($cat_id, $user_id)
    {
        $cat_id = (int)$cat_id;
        $user_id = (int)$user_id;
        
        $sql = "SELECT * FROM brands WHERE cat_id = $cat_id AND created_by = $user_id ORDER BY brand_name ASC";
        return $this->db_fetch_all($sql);
    }
    
    // get brand by id
    public function get_brand_by_id($brand_id, $user_id)
    {
        $brand_id = (int)$brand_id;
        $user_id = (int)$user_id;
        
        $sql = "SELECT b.*, c.cat_name 
                FROM brands b 
                LEFT JOIN categories c ON b.cat_id = c.cat_id 
                WHERE b.brand_id = $brand_id AND b.created_by = $user_id";
        return $this->db_fetch_one($sql);
    }
    
    // update brand
    public function update_brand($brand_id, $brand_name, $cat_id, $user_id)
    {
        $brand_id = (int)$brand_id;
        $brand_name = mysqli_real_escape_string($this->db_conn(), $brand_name);
        $cat_id = (int)$cat_id;
        $user_id = (int)$user_id;
        
        $sql = "UPDATE brands SET brand_name = '$brand_name', cat_id = $cat_id WHERE brand_id = $brand_id AND created_by = $user_id";
        return $this->db_query($sql);
    }
    
    // delete brand
    public function delete_brand($brand_id, $user_id)
    {
        $brand_id = (int)$brand_id;
        $user_id = (int)$user_id;
        
        $sql = "DELETE FROM brands WHERE brand_id = $brand_id AND created_by = $user_id";
        return $this->db_query($sql);
    }
    
    // get all brands
    public function get_all_brands()
    {
        $sql = "SELECT b.*, c.cat_name 
                FROM brands b 
                LEFT JOIN categories c ON b.cat_id = c.cat_id 
                ORDER BY c.cat_name, b.brand_name ASC";
        return $this->db_fetch_all($sql);
    }
}
?>