<?php
require_once("../settings/db_class.php");

class order_class extends db_connection
{
    // Create a new order
    public function create_order($customer_id, $invoice_amt, $order_date, $order_status)
    {
        $customer_id = (int)$customer_id;
        $invoice_amt = (float)$invoice_amt;
        $order_date = mysqli_real_escape_string($this->db_conn(), $order_date);
        $order_status = mysqli_real_escape_string($this->db_conn(), $order_status);
        
        $sql = "INSERT INTO orders (customer_id, invoice_amt, order_date, order_status) 
                VALUES ($customer_id, $invoice_amt, '$order_date', '$order_status')";
        
        if ($this->db_query($sql)) {
            // Return the last inserted order ID
            return mysqli_insert_id($this->db_conn());
        }
        return false;
    }
    
    // Add order details
    public function add_order_detail($order_id, $product_id, $qty, $price)
    {
        $order_id = (int)$order_id;
        $product_id = (int)$product_id;
        $qty = (int)$qty;
        $price = (float)$price;
        
        $sql = "INSERT INTO orderdetails (order_id, product_id, qty, price) 
                VALUES ($order_id, $product_id, $qty, $price)";
        
        return $this->db_query($sql);
    }
    
    // Record payment
    public function record_payment($order_id, $amount, $customer_id, $payment_date)
    {
        $order_id = (int)$order_id;
        $amount = (float)$amount;
        $customer_id = (int)$customer_id;
        $payment_date = mysqli_real_escape_string($this->db_conn(), $payment_date);
        
        $sql = "INSERT INTO payment (order_id, amt, customer_id, payment_date) 
                VALUES ($order_id, $amount, $customer_id, '$payment_date')";
        
        return $this->db_query($sql);
    }
    
    // Get all orders for a customer
    public function get_customer_orders($customer_id)
    {
        $customer_id = (int)$customer_id;
        
        $sql = "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC";
        return $this->db_fetch_all($sql);
    }
    
    // Get order by ID
    public function get_order_by_id($order_id)
    {
        $order_id = (int)$order_id;
        
        $sql = "SELECT * FROM orders WHERE order_id = $order_id";
        return $this->db_fetch_one($sql);
    }
    
    // Get order details for an order
    public function get_order_details($order_id)
    {
        $order_id = (int)$order_id;
        
        $sql = "SELECT od.*, p.product_title, p.product_image 
                FROM orderdetails od 
                LEFT JOIN products p ON od.product_id = p.product_id 
                WHERE od.order_id = $order_id";
        return $this->db_fetch_all($sql);
    }
    
    // Get payment for an order
    public function get_order_payment($order_id)
    {
        $order_id = (int)$order_id;
        
        $sql = "SELECT * FROM payment WHERE order_id = $order_id";
        return $this->db_fetch_one($sql);
    }
    
    // Update order status
    public function update_order_status($order_id, $status)
    {
        $order_id = (int)$order_id;
        $status = mysqli_real_escape_string($this->db_conn(), $status);
        
        $sql = "UPDATE orders SET order_status = '$status' WHERE order_id = $order_id";
        return $this->db_query($sql);
    }
    
    // Get all orders (for admin)
    public function get_all_orders()
    {
        $sql = "SELECT o.*, c.customer_name, c.customer_email 
                FROM orders o 
                LEFT JOIN customer c ON o.customer_id = c.customer_id 
                ORDER BY o.order_date DESC";
        return $this->db_fetch_all($sql);
    }
}
?>