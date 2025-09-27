<?php

require_once '../classes/category_class.php';

function add_category_ctr($cat_name) {
    $cont = new Category();
    return $cont->addCategory($cat_name);
}

function fetch_categories_ctr() {
    $cont = new Category();
    return $cont->getAll();
}

function update_category_ctr($cat_id, $cat_name) {
    $cont = new Category();
    return $cont->updateCategory($cat_id, $cat_name);
}

function delete_category_ctr($cat_id) {
    $cont = new Category();
    return $cont->deleteCategory($cat_id);
}
