<?php

header('Content-Type: application/json');
require_once '../settings/core.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

// Create uploads directory structure: uploads/uXX/pXX/
$upload_base = '../uploads';
$user_dir = $upload_base . '/u' . $user_id;
$product_dir = $user_dir . '/p' . ($product_id > 0 ? $product_id : 'temp_' . time());

// Create directories if they don't exist
if (!file_exists($user_dir)) {
    mkdir($user_dir, 0755, true);
}
if (!file_exists($product_dir)) {
    mkdir($product_dir, 0755, true);
}

// Validate file type
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$file_type = $_FILES['product_image']['type'];
if (!in_array($file_type, $allowed_types)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only images allowed']);
    exit();
}

// Generate unique filename
$extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
$filename = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
$target_path = $product_dir . '/' . $filename;

// Move uploaded file
if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path)) {
    // Return relative path for database storage
    $db_path = str_replace('../', '', $target_path);
    echo json_encode(['status' => 'success', 'message' => 'Image uploaded', 'path' => $db_path]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
}