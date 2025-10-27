<?php
require_once("../classes/category_class.php");

// Prevent function redeclaration
if (!function_exists('add_category_ctr')) {

    // add new category
    function add_category_ctr($cat_name, $user_id)
    {
        $category = new category_class();
        return $category->add_category($cat_name, $user_id);
    }

    // check if category exists
    function category_exists_ctr($cat_name, $user_id)
    {
        $category = new category_class();
        return $category->category_exists($cat_name, $user_id);
    }

    // get all categories for a user
    function get_user_categories_ctr($user_id)
    {
        $category = new category_class();
        return $category->get_user_categories($user_id);
    }

    // get category by id
    function get_category_by_id_ctr($cat_id, $user_id)
    {
        $category = new category_class();
        return $category->get_category_by_id($cat_id, $user_id);
    }

    // update category
    function update_category_ctr($cat_id, $cat_name, $user_id)
    {
        $category = new category_class();
        return $category->update_category($cat_id, $cat_name, $user_id);
    }

    // delete category
    function delete_category_ctr($cat_id, $user_id)
    {
        $category = new category_class();
        return $category->delete_category($cat_id, $user_id);
    }

    // get all categories (for general use)
    function get_all_categories_ctr()
    {
        $category = new category_class();
        return $category->get_all_categories();
    }

    // validate category name
    function validate_category_name($cat_name)
    {
        $result = array('valid' => true, 'message' => '');
        
        // check if empty
        if (empty(trim($cat_name))) {
            $result['valid'] = false;
            $result['message'] = 'Category name is required';
            return $result;
        }
        
        // check length
        if (strlen($cat_name) < 2) {
            $result['valid'] = false;
            $result['message'] = 'Category name must be at least 2 characters';
            return $result;
        }
        
        if (strlen($cat_name) > 100) {
            $result['valid'] = false;
            $result['message'] = 'Category name must not exceed 100 characters';
            return $result;
        }
        
        // check for valid characters (letters, numbers, spaces, hyphens, underscores)
        if (!preg_match('/^[a-zA-Z0-9\s\-_\.]{2,100}$/', $cat_name)) {
            $result['valid'] = false;
            $result['message'] = 'Category name can only contain letters, numbers, spaces, hyphens, underscores, and dots';
            return $result;
        }
        
        return $result;
    }

} 
?>