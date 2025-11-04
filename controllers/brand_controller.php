<?php

require_once '../classes/brand_class.php';

/**
 * Add a new brand
 */
function add_brand_ctr($brand_name, $cat_id) {
    $cont = new Brand();
    return $cont->addBrand($brand_name, $cat_id);
}

/**
 * Fetch all brands
 */
function fetch_brands_ctr() {
    $cont = new Brand();
    return $cont->getAllBrands();
}

/**
 * Update a brand
 */
function update_brand_ctr($brand_id, $brand_name) {
    $cont = new Brand();
    return $cont->updateBrand($brand_id, $brand_name);
}

/**
 * Delete a brand
 */
function delete_brand_ctr($brand_id) {
    $cont = new Brand();
    return $cont->deleteBrand($brand_id);
}