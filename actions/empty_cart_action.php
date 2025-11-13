<?php
require_once('../controllers/cart_controller.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $c_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
    
    $result = empty_cart_ctr($ip_add, $c_id);
    
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Cart emptied successfully',
            'cart_count' => 0
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to empty cart'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>