<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
if ($brand_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid brand id']);
    exit();
}

$res = delete_brand_ctr($brand_id);
if ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Brand deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete brand (not found)']);
}