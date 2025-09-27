<?php
require_once("../settings/db_class.php");

class category_class extends db_connection
{
    // add new category
    public function add_category($cat_name, $user_id)
    {
        // clean data
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);
        $user_id = (int)$user_id;
        
        $sql = "INSERT INTO categories (cat_name, created_by) VALUES ('$cat_name', $user_id)";
        return $this->db_query($sql);
    }
    
    // check if category name exists for this user
    public function category_exists($cat_name, $user_id)
    {
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);
        $user_id = (int)$user_id;
        
        $sql = "SELECT cat_id FROM categories WHERE cat_name = '$cat_name' AND created_by = $user_id";
        $result = $this->db_fetch_one($sql);
        
        return $result ? true : false;
    }
    
    // get all categories for a user
    public function get_user_categories($user_id)
    {
        $user_id = (int)$user_id;
        $sql = "SELECT * FROM categories WHERE created_by = $user_id ORDER BY cat_name ASC";
        return $this->db_fetch_all($sql);
    }
    
    // get category by id
    public function get_category_by_id($cat_id, $user_id)
    {
        $cat_id = (int)$cat_id;
        $user_id = (int)$user_id;
        
        $sql = "SELECT * FROM categories WHERE cat_id = $cat_id AND created_by = $user_id";
        return $this->db_fetch_one($sql);
    }
    
    // update category
    public function update_category($cat_id, $cat_name, $user_id)
    {
        $cat_id = (int)$cat_id;
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);
        $user_id = (int)$user_id;
        
        $sql = "UPDATE categories SET cat_name = '$cat_name' WHERE cat_id = $cat_id AND created_by = $user_id";
        return $this->db_query($sql);
    }
    
    // delete category
    public function delete_category($cat_id, $user_id)
    {
        $cat_id = (int)$cat_id;
        $user_id = (int)$user_id;
        
        $sql = "DELETE FROM categories WHERE cat_id = $cat_id AND created_by = $user_id";
        return $this->db_query($sql);
    }
    
    // get all categories (for general use)
    public function get_all_categories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        return $this->db_fetch_all($sql);
    }
}
?>