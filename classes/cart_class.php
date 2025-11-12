<?php
require_once("../settings/db_class.php");

class cart_class extends db_connection
{
    // Check if product already exists in cart
    public function check_product_in_cart($product_id, $customer_id)
    {
        $product_id = (int)$product_id;
        $customer_id = (int)$customer_id;
        
        $sql = "SELECT * FROM cart WHERE p_id = $product_id AND c_id = $customer_id";
        return $this->db_fetch_one($sql);
    }
    
    // Add product to cart (if doesn't exist)
    public function add_to_cart($product_id, $customer_id, $qty)
    {
        $product_id = (int)$product_id;
        $customer_id = (int)$customer_id;
        $qty = (int)$qty;
        
        $sql = "INSERT INTO cart (p_id, c_id, qty) VALUES ($product_id, $customer_id, $qty)";
        return $this->db_query($sql);
    }
    
    // Update quantity if product already in cart
    public function update_cart_quantity($product_id, $customer_id, $qty)
    {
        $product_id = (int)$product_id;
        $customer_id = (int)$customer_id;
        $qty = (int)$qty;
        
        $sql = "UPDATE cart SET qty = qty + $qty WHERE p_id = $product_id AND c_id = $customer_id";
        return $this->db_query($sql);
    }
    
    // Update quantity by cart ID
    public function update_cart_item($cart_id, $qty)
    {
        $cart_id = (int)$cart_id;
        $qty = (int)$qty;
        
        $sql = "UPDATE cart SET qty = $qty WHERE cart_id = $cart_id";
        return $this->db_query($sql);
    }
    
    // Get all cart items for a user
    public function get_user_cart($customer_id)
    {
        $customer_id = (int)$customer_id;
        
        $sql = "SELECT c.*, p.product_title, p.product_price, p.product_image, p.product_cat, p.product_brand 
                FROM cart c 
                LEFT JOIN products p ON c.p_id = p.product_id 
                WHERE c.c_id = $customer_id";
        return $this->db_fetch_all($sql);
    }
    
    // Remove item from cart
    public function remove_from_cart($cart_id)
    {
        $cart_id = (int)$cart_id;
        
        $sql = "DELETE FROM cart WHERE cart_id = $cart_id";
        return $this->db_query($sql);
    }
    
    // Remove item from cart by product and customer
    public function remove_product_from_cart($product_id, $customer_id)
    {
        $product_id = (int)$product_id;
        $customer_id = (int)$customer_id;
        
        $sql = "DELETE FROM cart WHERE p_id = $product_id AND c_id = $customer_id";
        return $this->db_query($sql);
    }
    
    // Empty entire cart for a user
    public function empty_cart($customer_id)
    {
        $customer_id = (int)$customer_id;
        
        $sql = "DELETE FROM cart WHERE c_id = $customer_id";
        return $this->db_query($sql);
    }
    
    // Get cart total for a user
    public function get_cart_total($customer_id)
    {
        $customer_id = (int)$customer_id;
        
        $sql = "SELECT SUM(p.product_price * c.qty) as total 
                FROM cart c 
                LEFT JOIN products p ON c.p_id = p.product_id 
                WHERE c.c_id = $customer_id";
        $result = $this->db_fetch_one($sql);
        return $result ? $result['total'] : 0;
    }
    
    // Get cart item count
    public function get_cart_count($customer_id)
    {
        $customer_id = (int)$customer_id;
        
        $sql = "SELECT COUNT(*) as count FROM cart WHERE c_id = $customer_id";
        $result = $this->db_fetch_one($sql);
        return $result ? $result['count'] : 0;
    }
}
?>