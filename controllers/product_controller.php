<?php
require_once("../classes/product_class.php");

// Prevent function redeclaration
if (!function_exists('add_product_ctr')) {

    // add new product
    function add_product_ctr($cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id)
    {
        $product = new product_class();
        return $product->add_product($cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id);
    }

    // update product
    function update_product_ctr($product_id, $cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id)
    {
        $product = new product_class();
        return $product->update_product($product_id, $cat_id, $brand_id, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $user_id);
    }

    // get product by id
    function get_product_by_id_ctr($product_id, $user_id)
    {
        $product = new product_class();
        return $product->get_product_by_id($product_id, $user_id);
    }

    // get all products for a user
    function get_user_products_ctr($user_id)
    {
        $product = new product_class();
        return $product->get_user_products($user_id);
    }

    // view all products (for customers)
    function view_all_products_ctr()
    {
        $product = new product_class();
        return $product->view_all_products();
    }

    // search products
    function search_products_ctr($query)
    {
        $product = new product_class();
        return $product->search_products($query);
    }

    // filter products by category
    function filter_products_by_category_ctr($cat_id)
    {
        $product = new product_class();
        return $product->filter_products_by_category($cat_id);
    }

    // filter products by brand
    function filter_products_by_brand_ctr($brand_id)
    {
        $product = new product_class();
        return $product->filter_products_by_brand($brand_id);
    }

    // view single product
    function view_single_product_ctr($product_id)
    {
        $product = new product_class();
        return $product->view_single_product($product_id);
    }

    // delete product
    function delete_product_ctr($product_id, $user_id)
    {
        $product = new product_class();
        return $product->delete_product($product_id, $user_id);
    }

    // validate product data
    function validate_product_data($product_title, $product_price, $product_desc)
    {
        $result = array('valid' => true, 'message' => '');
        
        // validate title
        if (empty(trim($product_title))) {
            $result['valid'] = false;
            $result['message'] = 'Product title is required';
            return $result;
        }
        
        if (strlen($product_title) < 3) {
            $result['valid'] = false;
            $result['message'] = 'Product title must be at least 3 characters';
            return $result;
        }
        
        if (strlen($product_title) > 200) {
            $result['valid'] = false;
            $result['message'] = 'Product title must not exceed 200 characters';
            return $result;
        }
        
        // validate price
        if (!is_numeric($product_price) || $product_price < 0) {
            $result['valid'] = false;
            $result['message'] = 'Product price must be a valid positive number';
            return $result;
        }
        
        // validate description
        if (empty(trim($product_desc))) {
            $result['valid'] = false;
            $result['message'] = 'Product description is required';
            return $result;
        }
        
        if (strlen($product_desc) < 10) {
            $result['valid'] = false;
            $result['message'] = 'Product description must be at least 10 characters';
            return $result;
        }
        
        return $result;
    }

} 
?>