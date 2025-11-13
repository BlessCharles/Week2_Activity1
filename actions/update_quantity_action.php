<?php
require_once('../controllers/cart_controller.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $p_id = isset($_POST['p_id']) ? intval($_POST['p_id']) : 0;
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
    
    if ($p_id <= 0 || $qty < 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product or quantity'
        ]);
        exit();
    }
    
    $c_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
    
    $result = update_cart_qty_ctr($p_id, $ip_add, $c_id, $qty);
    
    if ($result) {
        $cart_count = count_cart_items_ctr($ip_add, $c_id);
        $cart_total = get_cart_total_ctr($ip_add, $c_id);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Cart updated successfully',
            'cart_count' => $cart_count,
            'cart_total' => number_format($cart_total, 2)
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update cart'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>