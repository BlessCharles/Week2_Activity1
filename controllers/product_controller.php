<?php

require_once '../classes/product_class.php';

/**
 * Add a new product
 */
function add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $cont = new Product();
    return $cont->addProduct($cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

/**
 * Fetch all products
 */
function fetch_products_ctr() {
    $cont = new Product();
    return $cont->getAllProducts();
}

/**
 * Get product by ID
 */
function get_product_by_id_ctr($product_id) {
    $cont = new Product();
    return $cont->getProductById($product_id);
}

/**
 * Update a product
 */
function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $cont = new Product();
    return $cont->updateProduct($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

/**
 * Delete a product
 */
function delete_product_ctr($product_id) {
    $cont = new Product();
    return $cont->deleteProduct($product_id);
}