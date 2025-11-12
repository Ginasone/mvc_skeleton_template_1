<?php
require_once("../classes/cart_class.php");

if (!function_exists('add_to_cart_ctr')) {

    // Add to cart (checks if it exists first)
    function add_to_cart_ctr($product_id, $customer_id, $qty)
    {
        $cart = new cart_class();
        
        // Check if product already in cart
        $existing = $cart->check_product_in_cart($product_id, $customer_id);
        
        if ($existing) {
            // Update quantity if already exists
            return $cart->update_cart_quantity($product_id, $customer_id, $qty);
        } else {
            // Add new item
            return $cart->add_to_cart($product_id, $customer_id, $qty);
        }
    }
    
    // Update cart item quantity
    function update_cart_item_ctr($cart_id, $qty)
    {
        $cart = new cart_class();
        return $cart->update_cart_item($cart_id, $qty);
    }
    
    // Remove from cart
    function remove_from_cart_ctr($cart_id)
    {
        $cart = new cart_class();
        return $cart->remove_from_cart($cart_id);
    }
    
    // Get user cart
    function get_user_cart_ctr($customer_id)
    {
        $cart = new cart_class();
        return $cart->get_user_cart($customer_id);
    }
    
    // Empty cart
    function empty_cart_ctr($customer_id)
    {
        $cart = new cart_class();
        return $cart->empty_cart($customer_id);
    }
    
    // Get cart total
    function get_cart_total_ctr($customer_id)
    {
        $cart = new cart_class();
        return $cart->get_cart_total($customer_id);
    }
    
    // Get cart count
    function get_cart_count_ctr($customer_id)
    {
        $cart = new cart_class();
        return $cart->get_cart_count($customer_id);
    }

} 
?>