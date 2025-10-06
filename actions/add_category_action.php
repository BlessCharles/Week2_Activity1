<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
if ($cat_name === '') {
    echo json_encode(['status' => 'error', 'message' => 'Category name is required']);
    exit();
}

$res = add_category_ctr($cat_name);
if ($res === 'exists') {
    echo json_encode(['status' => 'error', 'message' => 'Category name already exists']);
} elseif ($res) {
    echo json_encode(['status' => 'success', 'message' => 'Category added', 'cat_id' => $res]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add category']);
}
