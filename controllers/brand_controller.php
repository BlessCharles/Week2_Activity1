<?php

require_once '../classes/brand_class.php';


function add_brand_ctr($brand_name, $cat_id) {
    $cont = new Brand();
    return $cont->addBrand($brand_name, $cat_id);
}


function fetch_brands_ctr() {
    $cont = new Brand();
    return $cont->getAllBrands();
}


function update_brand_ctr($brand_id, $brand_name) {
    $cont = new Brand();
    return $cont->updateBrand($brand_id, $brand_name);
}


function delete_brand_ctr($brand_id) {
    $cont = new Brand();
    return $cont->deleteBrand($brand_id);
}