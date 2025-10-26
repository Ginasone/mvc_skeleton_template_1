<?php
require_once("../classes/brand_class.php");

// add new brand
function add_brand_ctr($brand_name, $cat_id, $user_id)
{
    $brand = new brand_class();
    return $brand->add_brand($brand_name, $cat_id, $user_id);
}

// check if brand exists
function brand_exists_ctr($brand_name, $cat_id, $user_id)
{
    $brand = new brand_class();
    return $brand->brand_exists($brand_name, $cat_id, $user_id);
}

// get all brands for a user
function get_user_brands_ctr($user_id)
{
    $brand = new brand_class();
    return $brand->get_user_brands($user_id);
}

// get brands by category
function get_brands_by_category_ctr($cat_id, $user_id)
{
    $brand = new brand_class();
    return $brand->get_brands_by_category($cat_id, $user_id);
}

// get brand by id
function get_brand_by_id_ctr($brand_id, $user_id)
{
    $brand = new brand_class();
    return $brand->get_brand_by_id($brand_id, $user_id);
}

// update brand
function update_brand_ctr($brand_id, $brand_name, $cat_id, $user_id)
{
    $brand = new brand_class();
    return $brand->update_brand($brand_id, $brand_name, $cat_id, $user_id);
}

// delete brand
function delete_brand_ctr($brand_id, $user_id)
{
    $brand = new brand_class();
    return $brand->delete_brand($brand_id, $user_id);
}

// get all brands (for general use)
function get_all_brands_ctr()
{
    $brand = new brand_class();
    return $brand->get_all_brands();
}

// validate brand name
function validate_brand_name($brand_name)
{
    $result = array('valid' => true, 'message' => '');
    
    // check if empty
    if (empty(trim($brand_name))) {
        $result['valid'] = false;
        $result['message'] = 'Brand name is required';
        return $result;
    }
    
    // check length
    if (strlen($brand_name) < 2) {
        $result['valid'] = false;
        $result['message'] = 'Brand name must be at least 2 characters';
        return $result;
    }
    
    if (strlen($brand_name) > 100) {
        $result['valid'] = false;
        $result['message'] = 'Brand name must not exceed 100 characters';
        return $result;
    }
    
    // check for valid characters
    if (!preg_match('/^[a-zA-Z0-9\s\-_\.&]{2,100}$/', $brand_name)) {
        $result['valid'] = false;
        $result['message'] = 'Brand name can only contain letters, numbers, spaces, hyphens, underscores, dots, and ampersands';
        return $result;
    }
    
    return $result;
}
?>