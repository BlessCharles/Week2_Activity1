<?php
require_once('../controllers/order_controller.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to view orders'
    ]);
    exit();
}

$customer_id = $_SESSION['user_id'];
$orders = get_customer_orders_ctr($customer_id);

echo json_encode([
    'status' => 'success',
    'data' => $orders
]);
?>