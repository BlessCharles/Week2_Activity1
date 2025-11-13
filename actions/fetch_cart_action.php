<?php
require_once('../controllers/cart_controller.php');
session_start();

header('Content-Type: application/json');

$c_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$ip_add = $_SERVER['REMOTE_ADDR'];

// Get cart items
$cart_items = get_cart_items_ctr($ip_add, $c_id);
$cart_total = get_cart_total_ctr($ip_add, $c_id);
$cart_count = count_cart_items_ctr($ip_add, $c_id);

echo json_encode([
    'status' => 'success',
    'data' => $cart_items,
    'total' => $cart_total,
    'count' => $cart_count
]);
?>