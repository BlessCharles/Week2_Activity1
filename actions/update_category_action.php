<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';

if ($cat_id <= 0 || $cat_name === '') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

$res = update_category_ctr($cat_id, $cat_name);
if ($res === 'exists') {
    echo json_encode(['status' => 'error', 'message' => 'Another category with that name already exists']);
} elseif ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Category updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update category']);
}
