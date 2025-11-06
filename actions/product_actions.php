<?php

header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

// Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'view_all':
        $products = view_all_products_ctr();
        echo json_encode(['status' => 'success', 'data' => $products]);
        break;

    case 'search':
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($query === '') {
            echo json_encode(['status' => 'error', 'message' => 'Search query is required']);
            exit();
        }
        $products = search_products_ctr($query);
        echo json_encode(['status' => 'success', 'data' => $products]);
        break;

    case 'filter_category':
        $cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
        if ($cat_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid category ID']);
            exit();
        }
        $products = filter_products_by_category_ctr($cat_id);
        echo json_encode(['status' => 'success', 'data' => $products]);
        break;

    case 'filter_brand':
        $brand_id = isset($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;
        if ($brand_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid brand ID']);
            exit();
        }
        $products = filter_products_by_brand_ctr($brand_id);
        echo json_encode(['status' => 'success', 'data' => $products]);
        break;

    case 'view_single':
        $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($product_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
            exit();
        }
        $product = view_single_product_ctr($product_id);
        if ($product) {
            echo json_encode(['status' => 'success', 'data' => $product]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
