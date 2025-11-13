<?php
require_once('../controllers/cart_controller.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get product details
    $p_id = isset($_POST['p_id']) ? intval($_POST['p_id']) : 0;
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
    
    // Validate inputs
    if ($p_id <= 0 || $qty <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product or quantity'
        ]);
        exit();
    }
    
    // Get customer ID if logged in
    $c_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    // Get IP address for guest users
    $ip_add = $_SERVER['REMOTE_ADDR'];
    
    // Add to cart
    $result = add_to_cart_ctr($p_id, $ip_add, $c_id, $qty);
    
    if ($result) {
        // Get updated cart count
        $cart_count = count_cart_items_ctr($ip_add, $c_id);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'cart_count' => $cart_count
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add product to cart'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>