<?php
require_once("../settings/db_class.php");

class product_class extends db_connection
{
    // add new product
    public function add_product($cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id)
    {
        $cat_id = (int)$cat_id;
        $brand_id = (int)$brand_id;
        $product_title = mysqli_real_escape_string($this->db_conn(), $product_title);
        $product_price = (float)$product_price;
        $product_desc = mysqli_real_escape_string($this->db_conn(), $product_desc);
        $product_image = mysqli_real_escape_string($this->db_conn(), $product_image);
        $product_keywords = mysqli_real_escape_string($this->db_conn(), $product_keywords);
        $user_id = (int)$user_id;
        
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords, created_by) 
                VALUES ($cat_id, $brand_id, '$product_title', $product_price, '$product_desc', '$product_image', '$product_keywords', $user_id)";
        return $this->db_query($sql);
    }
    
    // update product
    public function update_product($product_id, $cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id)
    {
        $product_id = (int)$product_id;
        $cat_id = (int)$cat_id;
        $brand_id = (int)$brand_id;
        $product_title = mysqli_real_escape_string($this->db_conn(), $product_title);
        $product_price = (float)$product_price;
        $product_desc = mysqli_real_escape_string($this->db_conn(), $product_desc);
        $product_image = mysqli_real_escape_string($this->db_conn(), $product_image);
        $product_keywords = mysqli_real_escape_string($this->db_conn(), $product_keywords);
        $user_id = (int)$user_id;
        
        $sql = "UPDATE products SET 
                product_cat = $cat_id,
                product_brand = $brand_id,
                product_title = '$product_title',
                product_price = $product_price,
                product_desc = '$product_desc',
                product_image = '$product_image',
                product_keywords = '$product_keywords'
                WHERE product_id = $product_id AND created_by = $user_id";
        
        return $this->db_query($sql);
    }
    
    // get product by id
    public function get_product_by_id($product_id, $user_id)
    {
        $product_id = (int)$product_id;
        $user_id = (int)$user_id;
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                WHERE p.product_id = $product_id AND p.created_by = $user_id";
        return $this->db_fetch_one($sql);
    }
    
    // get all products for a user
    public function get_user_products($user_id)
    {
        $user_id = (int)$user_id;
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                WHERE p.created_by = $user_id 
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // view all products (for customers)
    public function view_all_products()
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // search products
    public function search_products($query)
    {
        $query = mysqli_real_escape_string($this->db_conn(), $query);
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                WHERE p.product_title LIKE '%$query%' 
                OR p.product_desc LIKE '%$query%' 
                OR p.product_keywords LIKE '%$query%'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // filter products by category
    public function filter_products_by_category($cat_id)
    {
        $cat_id = (int)$cat_id;
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                WHERE p.product_cat = $cat_id 
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // filter products by brand
    public function filter_products_by_brand($brand_id)
    {
        $brand_id = (int)$brand_id;
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                WHERE p.product_brand = $brand_id 
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // view single product (for customers)
    public function view_single_product($product_id)
    {
        $product_id = (int)$product_id;
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p 
                LEFT JOIN categories c ON p.product_cat = c.cat_id 
                LEFT JOIN brands b ON p.product_brand = b.brand_id 
                WHERE p.product_id = $product_id";
        return $this->db_fetch_one($sql);
    }
    
    // delete product
    public function delete_product($product_id, $user_id)
    {
        $product_id = (int)$product_id;
        $user_id = (int)$user_id;
        
        $sql = "DELETE FROM products WHERE product_id = $product_id AND created_by = $user_id";
        return $this->db_query($sql);
    }
}
?>