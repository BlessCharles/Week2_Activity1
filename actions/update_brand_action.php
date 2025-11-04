<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

if ($brand_id <= 0 || $brand_name === '') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

$res = update_brand_ctr($brand_id, $brand_name);
if ($res === 'exists') {
    echo json_encode(['status' => 'error', 'message' => 'Another brand with that name already exists']);
} elseif ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Brand updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update brand']);
}