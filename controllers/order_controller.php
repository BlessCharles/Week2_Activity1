<?php
// Connect to the Order class
require_once(dirname(__FILE__).'/../classes/order_class.php');

// Create order
function create_order_ctr($customer_id, $invoice_no, $order_date, $order_status) {
    $order = new Order();
    return $order->create_order($customer_id, $invoice_no, $order_date, $order_status);
}

// Add order details
function add_order_details_ctr($order_id, $product_id, $qty) {
    $order = new Order();
    return $order->add_order_details($order_id, $product_id, $qty);
}

// Record payment
function record_payment_ctr($amt, $customer_id, $order_id, $currency, $payment_date) {
    $order = new Order();
    return $order->record_payment($amt, $customer_id, $order_id, $currency, $payment_date);
}

// Get customer orders
function get_customer_orders_ctr($customer_id) {
    $order = new Order();
    return $order->get_customer_orders($customer_id);
}

// Get order details
function get_order_details_ctr($order_id) {
    $order = new Order();
    return $order->get_order_details($order_id);
}

// Get single order
function get_order_ctr($order_id) {
    $order = new Order();
    return $order->get_order($order_id);
}

// Get all orders (admin)
function get_all_orders_ctr() {
    $order = new Order();
    return $order->get_all_orders();
}

// Update order status
function update_order_status_ctr($order_id, $status) {
    $order = new Order();
    return $order->update_order_status($order_id, $status);
}

// Generate invoice number
function generate_invoice_number_ctr() {
    $order = new Order();
    return $order->generate_invoice_number();
}
?>