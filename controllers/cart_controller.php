<?php
// Connect to the Cart class
require_once(dirname(__FILE__).'/../classes/cart_class.php');

// Add to cart
function add_to_cart_ctr($p_id, $ip_add, $c_id, $qty) {
    $cart = new Cart();
    return $cart->add_to_cart($p_id, $ip_add, $c_id, $qty);
}

// Check if product is in cart
function check_product_in_cart_ctr($p_id, $ip_add, $c_id) {
    $cart = new Cart();
    return $cart->check_product_in_cart($p_id, $ip_add, $c_id);
}

// Get cart items
function get_cart_items_ctr($ip_add, $c_id) {
    $cart = new Cart();
    return $cart->get_cart_items($ip_add, $c_id);
}

// Update cart quantity
function update_cart_qty_ctr($p_id, $ip_add, $c_id, $qty) {
    $cart = new Cart();
    return $cart->update_cart_qty($p_id, $ip_add, $c_id, $qty);
}

// Remove from cart
function remove_from_cart_ctr($p_id, $ip_add, $c_id) {
    $cart = new Cart();
    return $cart->remove_from_cart($p_id, $ip_add, $c_id);
}

// Empty cart
function empty_cart_ctr($ip_add, $c_id) {
    $cart = new Cart();
    return $cart->empty_cart($ip_add, $c_id);
}

// Get cart total
function get_cart_total_ctr($ip_add, $c_id) {
    $cart = new Cart();
    return $cart->get_cart_total($ip_add, $c_id);
}

// Count cart items
function count_cart_items_ctr($ip_add, $c_id) {
    $cart = new Cart();
    return $cart->count_cart_items($ip_add, $c_id);
}
?>