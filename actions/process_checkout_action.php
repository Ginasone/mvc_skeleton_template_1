<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';
require_once '../controllers/order_controller.php';

header('Content-Type: application/json');

$response = array();

try {
    // Check if user is logged in
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Please login first';
        echo json_encode($response);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $customer_id = get_user_id();
    
    // Step 1: Get cart items
    $cart_items = get_user_cart_ctr($customer_id);
    
    if (!$cart_items || count($cart_items) == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Your cart is empty';
        echo json_encode($response);
        exit;
    }
    
    // Step 2: Calculate total
    $total = get_cart_total_ctr($customer_id);
    
    if ($total <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid cart total';
        echo json_encode($response);
        exit;
    }
    
    // Step 3: Generate order reference
    $order_reference = generate_order_reference();
    
    // Step 4: Create order
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'Pending'; // Can be: Pending, Processing, Completed, Cancelled
    
    $order_id = create_order_ctr($customer_id, $total, $order_date, $order_status);
    
    if (!$order_id) {
        $response['status'] = 'error';
        $response['message'] = 'Failed to create order';
        echo json_encode($response);
        exit;
    }
    
    // Step 5: Add order details for each cart item
    $order_details_success = true;
    foreach ($cart_items as $item) {
        $result = add_order_details_ctr(
            $order_id,
            $item['p_id'],
            $item['qty'],
            $item['product_price']
        );
        
        if (!$result) {
            $order_details_success = false;
            break;
        }
    }
    
    if (!$order_details_success) {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add order details';
        echo json_encode($response);
        exit;
    }
    
    // Step 6: Record payment (simulated)
    $payment_date = date('Y-m-d H:i:s');
    $payment_result = record_payment_ctr($order_id, $total, $customer_id, $payment_date);
    
    if (!$payment_result) {
        $response['status'] = 'error';
        $response['message'] = 'Failed to record payment';
        echo json_encode($response);
        exit;
    }
    
    // Step 7: Empty the cart
    $empty_result = empty_cart_ctr($customer_id);
    
    if (!$empty_result) {
        // Not critical - order is already placed
        // Log this but don't fail the checkout
    }
    
    // Step 8: Return success response
    $response['status'] = 'success';
    $response['message'] = 'Order placed successfully!';
    $response['order_id'] = $order_id;
    $response['order_reference'] = $order_reference;
    $response['total'] = number_format($total, 2);
    $response['items_count'] = count($cart_items);

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>