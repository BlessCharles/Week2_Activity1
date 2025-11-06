<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$title = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
$price = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0;
$desc = isset($_POST['product_desc']) ? trim($_POST['product_desc']) : '';
$keywords = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';
$image_path = isset($_POST['image_path']) ? $_POST['image_path'] : '';

if ($product_id <= 0 || $cat_id <= 0 || $brand_id <= 0 || $title === '' || $price <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

$res = update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image_path, $keywords);
if ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
}