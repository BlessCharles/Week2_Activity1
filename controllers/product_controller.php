<?php

require_once '../classes/product_class.php';


function add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $cont = new Product();
    return $cont->addProduct($cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}


function fetch_products_ctr() {
    $cont = new Product();
    return $cont->getAllProducts();
}


function view_all_products_ctr() {
    $cont = new Product();
    return $cont->view_all_products();
}


function search_products_ctr($query) {
    $cont = new Product();
    return $cont->search_products($query);
}


function filter_products_by_category_ctr($cat_id) {
    $cont = new Product();
    return $cont->filter_products_by_category($cat_id);
}


function filter_products_by_brand_ctr($brand_id) {
    $cont = new Product();
    return $cont->filter_products_by_brand($brand_id);
}


function view_single_product_ctr($id) {
    $cont = new Product();
    return $cont->view_single_product($id);
}


function get_product_by_id_ctr($product_id) {
    $cont = new Product();
    return $cont->getProductById($product_id);
}


function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $cont = new Product();
    return $cont->updateProduct($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}


function delete_product_ctr($product_id) {
    $cont = new Product();
    return $cont->deleteProduct($product_id);
}