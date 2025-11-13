<?php
require_once('../controllers/cart_controller.php');
require_once('../controllers/order_controller.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please login to complete checkout'
        ]);
        exit();
    }
    
    $customer_id = $_SESSION['user_id'];
    $ip_add = $_SERVER['REMOTE_ADDR'];
    
    // Get cart items
    $cart_items = get_cart_items_ctr($ip_add, $customer_id);
    
    if (empty($cart_items)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Your cart is empty'
        ]);
        exit();
    }
    
    // Calculate total
    $total_amount = get_cart_total_ctr($ip_add, $customer_id);
    
    if ($total_amount <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid cart total'
        ]);
        exit();
    }
    
    // Generate unique invoice number
    $invoice_no = generate_invoice_number_ctr();
    $order_date = date('Y-m-d');
    $order_status = 'Pending'; // Can be: Pending, Processing, Completed, Cancelled
    
    // Create order
    $order_id = create_order_ctr($customer_id, $invoice_no, $order_date, $order_status);
    
    if (!$order_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to create order'
        ]);
        exit();
    }
    
    // Add order details for each cart item
    $details_added = true;
    foreach ($cart_items as $item) {
        if (!add_order_details_ctr($order_id, $item['p_id'], $item['qty'])) {
            $details_added = false;
            break;
        }
    }
    
    if (!$details_added) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to process order details'
        ]);
        exit();
    }
    
    // Record payment
    $currency = 'GHS'; // Ghana Cedis
    $payment_date = date('Y-m-d');
    
    $payment_recorded = record_payment_ctr($total_amount, $customer_id, $order_id, $currency, $payment_date);
    
    if (!$payment_recorded) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to record payment'
        ]);
        exit();
    }
    
    // Empty the cart
    $cart_emptied = empty_cart_ctr($ip_add, $customer_id);
    
    if (!$cart_emptied) {
        // Log this but don't fail the checkout
        error_log("Failed to empty cart for customer: $customer_id");
    }
    
    // Success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Order placed successfully!',
        'order_id' => $order_id,
        'invoice_no' => $invoice_no,
        'total_amount' => number_format($total_amount, 2),
        'currency' => $currency
    ]);
    
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>