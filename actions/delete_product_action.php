<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
if ($product_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product id']);
    exit();
}

$res = delete_product_ctr($product_id);
if ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
}