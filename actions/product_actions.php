<?php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

$response = array();

try {
    // Get the action type from request
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    switch ($action) {
        case 'get_all':
            // Get all products
            $products = view_all_products_ctr();
            
            if ($products !== false) {
                $response['status'] = 'success';
                $response['data'] = $products;
                $response['count'] = count($products);
            } else {
                $response['status'] = 'success';
                $response['data'] = array();
                $response['count'] = 0;
            }
            break;
            
        case 'get_single':
            // Get single product by ID
            $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($product_id <= 0) {
                $response['status'] = 'error';
                $response['message'] = 'Invalid product ID';
            } else {
                $product = view_single_product_ctr($product_id);
                
                if ($product) {
                    $response['status'] = 'success';
                    $response['data'] = $product;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Product not found';
                }
            }
            break;
            
        case 'search':
            // Search products by query
            $query = isset($_GET['query']) ? trim($_GET['query']) : '';
            
            if (empty($query)) {
                $response['status'] = 'error';
                $response['message'] = 'Search query is required';
            } else {
                $products = search_products_ctr($query);
                
                if ($products !== false) {
                    $response['status'] = 'success';
                    $response['data'] = $products;
                    $response['count'] = count($products);
                    $response['query'] = $query;
                } else {
                    $response['status'] = 'success';
                    $response['data'] = array();
                    $response['count'] = 0;
                    $response['query'] = $query;
                }
            }
            break;
            
        case 'filter_by_category':
            // Filter products by category
            $cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
            
            if ($cat_id <= 0) {
                $response['status'] = 'error';
                $response['message'] = 'Invalid category ID';
            } else {
                $products = filter_products_by_category_ctr($cat_id);
                
                if ($products !== false) {
                    $response['status'] = 'success';
                    $response['data'] = $products;
                    $response['count'] = count($products);
                    $response['filter'] = 'category';
                    $response['filter_id'] = $cat_id;
                } else {
                    $response['status'] = 'success';
                    $response['data'] = array();
                    $response['count'] = 0;
                    $response['filter'] = 'category';
                    $response['filter_id'] = $cat_id;
                }
            }
            break;
            
        case 'filter_by_brand':
            // Filter products by brand
            $brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
            
            if ($brand_id <= 0) {
                $response['status'] = 'error';
                $response['message'] = 'Invalid brand ID';
            } else {
                $products = filter_products_by_brand_ctr($brand_id);
                
                if ($products !== false) {
                    $response['status'] = 'success';
                    $response['data'] = $products;
                    $response['count'] = count($products);
                    $response['filter'] = 'brand';
                    $response['filter_id'] = $brand_id;
                } else {
                    $response['status'] = 'success';
                    $response['data'] = array();
                    $response['count'] = 0;
                    $response['filter'] = 'brand';
                    $response['filter_id'] = $brand_id;
                }
            }
            break;
            
        case 'filter_combined':
            // Filter by both category and brand
            $cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
            $brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
            
            if ($cat_id <= 0 && $brand_id <= 0) {
                $response['status'] = 'error';
                $response['message'] = 'At least one filter is required';
            } else {
                // Get products based on filters
                if ($cat_id > 0 && $brand_id > 0) {
                    // Filter by both
                    $products = filter_products_by_category_ctr($cat_id);
                    // Further filter by brand
                    if ($products && count($products) > 0) {
                        $filtered = array();
                        foreach ($products as $product) {
                            if ($product['product_brand'] == $brand_id) {
                                $filtered[] = $product;
                            }
                        }
                        $products = $filtered;
                    }
                } elseif ($cat_id > 0) {
                    // Filter by category only
                    $products = filter_products_by_category_ctr($cat_id);
                } else {
                    // Filter by brand only
                    $products = filter_products_by_brand_ctr($brand_id);
                }
                
                if ($products !== false) {
                    $response['status'] = 'success';
                    $response['data'] = $products;
                    $response['count'] = count($products);
                    $response['filters'] = array(
                        'category' => $cat_id,
                        'brand' => $brand_id
                    );
                } else {
                    $response['status'] = 'success';
                    $response['data'] = array();
                    $response['count'] = 0;
                    $response['filters'] = array(
                        'category' => $cat_id,
                        'brand' => $brand_id
                    );
                }
            }
            break;
            
        case 'search_with_filters':
            // Search with additional filters
            $query = isset($_GET['query']) ? trim($_GET['query']) : '';
            $cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
            $brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
            
            if (empty($query)) {
                $response['status'] = 'error';
                $response['message'] = 'Search query is required';
            } else {
                // First search by query
                $products = search_products_ctr($query);
                
                // Then apply filters
                if ($products && count($products) > 0) {
                    $filtered = array();
                    foreach ($products as $product) {
                        $match = true;
                        
                        if ($cat_id > 0 && $product['product_cat'] != $cat_id) {
                            $match = false;
                        }
                        
                        if ($brand_id > 0 && $product['product_brand'] != $brand_id) {
                            $match = false;
                        }
                        
                        if ($match) {
                            $filtered[] = $product;
                        }
                    }
                    $products = $filtered;
                }
                
                $response['status'] = 'success';
                $response['data'] = $products ? $products : array();
                $response['count'] = $products ? count($products) : 0;
                $response['query'] = $query;
                $response['filters'] = array(
                    'category' => $cat_id,
                    'brand' => $brand_id
                );
            }
            break;
            
        case 'get_categories':
            // Get all categories for filter dropdowns
            require_once '../controllers/category_controller.php';
            $categories = get_all_categories_ctr();
            
            if ($categories !== false) {
                $response['status'] = 'success';
                $response['data'] = $categories;
                $response['count'] = count($categories);
            } else {
                $response['status'] = 'success';
                $response['data'] = array();
                $response['count'] = 0;
            }
            break;
            
        case 'get_brands':
            // Get all brands for filter dropdowns
            require_once '../controllers/brand_controller.php';
            $brands = get_all_brands_ctr();
            
            if ($brands !== false) {
                $response['status'] = 'success';
                $response['data'] = $brands;
                $response['count'] = count($brands);
            } else {
                $response['status'] = 'success';
                $response['data'] = array();
                $response['count'] = 0;
            }
            break;
            
        default:
            $response['status'] = 'error';
            $response['message'] = 'Invalid action specified';
            $response['available_actions'] = array(
                'get_all' => 'Get all products',
                'get_single' => 'Get single product (requires id)',
                'search' => 'Search products (requires query)',
                'filter_by_category' => 'Filter by category (requires cat_id)',
                'filter_by_brand' => 'Filter by brand (requires brand_id)',
                'filter_combined' => 'Filter by category and/or brand',
                'search_with_filters' => 'Search with filters',
                'get_categories' => 'Get all categories',
                'get_brands' => 'Get all brands'
            );
            break;
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>