<?php
require_once("../classes/order_class.php");

if (!function_exists('create_order_ctr')) {

    // Create order
    function create_order_ctr($customer_id, $invoice_amt, $order_date, $order_status)
    {
        $order = new order_class();
        return $order->create_order($customer_id, $invoice_amt, $order_date, $order_status);
    }
    
    // Add order details
    function add_order_details_ctr($order_id, $product_id, $qty, $price)
    {
        $order = new order_class();
        return $order->add_order_detail($order_id, $product_id, $qty, $price);
    }
    
    // Record payment
    function record_payment_ctr($order_id, $amount, $customer_id, $payment_date)
    {
        $order = new order_class();
        return $order->record_payment($order_id, $amount, $customer_id, $payment_date);
    }
    
    // Get customer orders
    function get_customer_orders_ctr($customer_id)
    {
        $order = new order_class();
        return $order->get_customer_orders($customer_id);
    }
    
    // Get order by ID
    function get_order_by_id_ctr($order_id)
    {
        $order = new order_class();
        return $order->get_order_by_id($order_id);
    }
    
    // Get order details
    function get_order_details_ctr($order_id)
    {
        $order = new order_class();
        return $order->get_order_details($order_id);
    }
    
    // Get order payment
    function get_order_payment_ctr($order_id)
    {
        $order = new order_class();
        return $order->get_order_payment($order_id);
    }
    
    // Update order status
    function update_order_status_ctr($order_id, $status)
    {
        $order = new order_class();
        return $order->update_order_status($order_id, $status);
    }
    
    // Get all orders (admin)
    function get_all_orders_ctr()
    {
        $order = new order_class();
        return $order->get_all_orders();
    }
    
    // Generate unique order reference
    function generate_order_reference()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

} 
?>