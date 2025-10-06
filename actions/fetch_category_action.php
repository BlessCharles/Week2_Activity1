<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';


if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$data = fetch_categories_ctr();
echo json_encode(['status' => 'success', 'data' => $data]);
