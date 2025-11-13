<?php
// Connect to database class
require_once(dirname(__FILE__).'/../settings/db_class.php');

class Order extends db_connection {
    
    // Create a new order
    public function create_order($customer_id, $invoice_no, $order_date, $order_status) {
        $customer_id = mysqli_real_escape_string($this->db_conn(), $customer_id);
        $invoice_no = mysqli_real_escape_string($this->db_conn(), $invoice_no);
        $order_date = mysqli_real_escape_string($this->db_conn(), $order_date);
        $order_status = mysqli_real_escape_string($this->db_conn(), $order_status);
        
        $sql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status) 
                VALUES ('$customer_id', '$invoice_no', '$order_date', '$order_status')";
        
        if ($this->db_write_query($sql)) {
            // Return the last inserted order ID
            return $this->last_insert_id();
        }
        return false;
    }
    
    // Add order details
    public function add_order_details($order_id, $product_id, $qty) {
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        $product_id = mysqli_real_escape_string($this->db_conn(), $product_id);
        $qty = mysqli_real_escape_string($this->db_conn(), $qty);
        
        $sql = "INSERT INTO orderdetails (order_id, product_id, qty) VALUES ('$order_id', '$product_id', '$qty')";
        return $this->db_write_query($sql);
    }
    
    // Record payment
    public function record_payment($amt, $customer_id, $order_id, $currency, $payment_date) {
        $amt = mysqli_real_escape_string($this->db_conn(), $amt);
        $customer_id = mysqli_real_escape_string($this->db_conn(), $customer_id);
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        $currency = mysqli_real_escape_string($this->db_conn(), $currency);
        $payment_date = mysqli_real_escape_string($this->db_conn(), $payment_date);
        
        $sql = "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date) 
                VALUES ('$amt', '$customer_id', '$order_id', '$currency', '$payment_date')";
        return $this->db_write_query($sql);
    }
    
    // Get all orders for a customer
    public function get_customer_orders($customer_id) {
        $customer_id = mysqli_real_escape_string($this->db_conn(), $customer_id);
        
        $sql = "SELECT o.*, p.amt, p.currency, p.payment_date,
                COUNT(od.product_id) as total_items
                FROM orders o
                LEFT JOIN payment p ON o.order_id = p.order_id
                LEFT JOIN orderdetails od ON o.order_id = od.order_id
                WHERE o.customer_id = '$customer_id'
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        return $this->db_fetch_all($sql);
    }
    
    // Get order details
    public function get_order_details($order_id) {
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        
        $sql = "SELECT od.*, p.product_title, p.product_price, p.product_image,
                (p.product_price * od.qty) as subtotal
                FROM orderdetails od
                JOIN products p ON od.product_id = p.product_id
                WHERE od.order_id = '$order_id'";
        return $this->db_fetch_all($sql);
    }
    
    // Get single order
    public function get_order($order_id) {
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        
        $sql = "SELECT o.*, c.customer_name, c.customer_email, c.customer_contact,
                c.customer_city, c.customer_country, p.amt, p.currency, p.payment_date
                FROM orders o
                JOIN customer c ON o.customer_id = c.customer_id
                LEFT JOIN payment p ON o.order_id = p.order_id
                WHERE o.order_id = '$order_id'";
        return $this->db_fetch_one($sql);
    }
    
    // Get all orders (for admin)
    public function get_all_orders() {
        $sql = "SELECT o.*, c.customer_name, c.customer_email, p.amt, p.currency,
                COUNT(od.product_id) as total_items
                FROM orders o
                JOIN customer c ON o.customer_id = c.customer_id
                LEFT JOIN payment p ON o.order_id = p.order_id
                LEFT JOIN orderdetails od ON o.order_id = od.order_id
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        return $this->db_fetch_all($sql);
    }
    
    // Update order status
    public function update_order_status($order_id, $status) {
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        $status = mysqli_real_escape_string($this->db_conn(), $status);
        
        $sql = "UPDATE orders SET order_status = '$status' WHERE order_id = '$order_id'";
        return $this->db_write_query($sql);
    }
    
    // Check if invoice number exists
    public function invoice_exists($invoice_no) {
        $invoice_no = mysqli_real_escape_string($this->db_conn(), $invoice_no);
        
        $sql = "SELECT order_id FROM orders WHERE invoice_no = '$invoice_no'";
        $result = $this->db_fetch_one($sql);
        return $result ? true : false;
    }
    
    // Generate unique invoice number
    public function generate_invoice_number() {
        do {
            // Generate invoice: INV-TIMESTAMP-RANDOM
            $invoice_no = 'INV-' . time() . '-' . rand(1000, 9999);
        } while ($this->invoice_exists($invoice_no));
        
        return $invoice_no;
    }
}
?>