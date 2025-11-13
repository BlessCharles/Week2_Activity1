<?php
require_once('../controllers/cart_controller.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $p_id = isset($_POST['p_id']) ? intval($_POST['p_id']) : 0;
    
    if ($p_id <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product ID'
        ]);
        exit();
    }
    
    $c_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
    
    $result = remove_from_cart_ctr($p_id, $ip_add, $c_id);
    
    if ($result) {
        $cart_count = count_cart_items_ctr($ip_add, $c_id);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Product removed from cart',
            'cart_count' => $cart_count
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to remove product from cart'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>