<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;

if ($brand_name === '' || $cat_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Brand name and category are required']);
    exit();
}

$res = add_brand_ctr($brand_name, $cat_id);
if ($res === 'exists') {
    echo json_encode(['status' => 'error', 'message' => 'Brand name already exists']);
} elseif ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Brand added successfully', 'brand_id' => $res]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add brand']);
}