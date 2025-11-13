<?php
// Connect to database class
require_once(dirname(__FILE__).'/../settings/db_class.php');

class Cart extends db_connection {
    
    // Add product to cart
    public function add_to_cart($p_id, $ip_add, $c_id, $qty) {
        // Check if product already exists in cart
        if ($this->check_product_in_cart($p_id, $ip_add, $c_id)) {
            // If exists, update quantity instead
            return $this->update_cart_quantity_on_add($p_id, $ip_add, $c_id, $qty);
        }
        
        // Escape inputs to prevent SQL injection
        $p_id = mysqli_real_escape_string($this->db_conn(), $p_id);
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        $qty = mysqli_real_escape_string($this->db_conn(), $qty);
        
        // If not exists, insert new cart item
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES ('$p_id', '$ip_add', '$c_id', '$qty')";
        } else {
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES ('$p_id', '$ip_add', NULL, '$qty')";
        }
        
        return $this->db_write_query($sql);
    }
    
    // Check if product exists in cart
    public function check_product_in_cart($p_id, $ip_add, $c_id) {
        $p_id = mysqli_real_escape_string($this->db_conn(), $p_id);
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        
        if ($c_id) {
            // For logged-in users
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND c_id = '$c_id'";
        } else {
            // For guest users (by IP)
            $sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND ip_add = '$ip_add' AND c_id IS NULL";
        }
        
        $result = $this->db_fetch_one($sql);
        return $result ? true : false;
    }
    
    // Update quantity when adding existing product
    private function update_cart_quantity_on_add($p_id, $ip_add, $c_id, $qty) {
        $p_id = mysqli_real_escape_string($this->db_conn(), $p_id);
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        $qty = mysqli_real_escape_string($this->db_conn(), $qty);
        
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "UPDATE cart SET qty = qty + '$qty' WHERE p_id = '$p_id' AND c_id = '$c_id'";
        } else {
            $sql = "UPDATE cart SET qty = qty + '$qty' WHERE p_id = '$p_id' AND ip_add = '$ip_add' AND c_id IS NULL";
        }
        
        return $this->db_write_query($sql);
    }
    
    // Get all cart items for a user
    public function get_cart_items($ip_add, $c_id) {
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        
        if ($c_id) {
            // For logged-in users
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "SELECT c.*, p.product_title, p.product_price, p.product_image, 
                    p.product_desc, cat.cat_name, b.brand_name,
                    (p.product_price * c.qty) as subtotal
                    FROM cart c
                    JOIN products p ON c.p_id = p.product_id
                    LEFT JOIN categories cat ON p.product_cat = cat.cat_id
                    LEFT JOIN brands b ON p.product_brand = b.brand_id
                    WHERE c.c_id = '$c_id'";
        } else {
            // For guest users
            $sql = "SELECT c.*, p.product_title, p.product_price, p.product_image, 
                    p.product_desc, cat.cat_name, b.brand_name,
                    (p.product_price * c.qty) as subtotal
                    FROM cart c
                    JOIN products p ON c.p_id = p.product_id
                    LEFT JOIN categories cat ON p.product_cat = cat.cat_id
                    LEFT JOIN brands b ON p.product_brand = b.brand_id
                    WHERE c.ip_add = '$ip_add' AND c.c_id IS NULL";
        }
        
        return $this->db_fetch_all($sql);
    }
    
    // Update cart item quantity
    public function update_cart_qty($p_id, $ip_add, $c_id, $qty) {
        if ($qty <= 0) {
            // If quantity is 0 or less, remove item
            return $this->remove_from_cart($p_id, $ip_add, $c_id);
        }
        
        $p_id = mysqli_real_escape_string($this->db_conn(), $p_id);
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        $qty = mysqli_real_escape_string($this->db_conn(), $qty);
        
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "UPDATE cart SET qty = '$qty' WHERE p_id = '$p_id' AND c_id = '$c_id'";
        } else {
            $sql = "UPDATE cart SET qty = '$qty' WHERE p_id = '$p_id' AND ip_add = '$ip_add' AND c_id IS NULL";
        }
        
        return $this->db_write_query($sql);
    }
    
    // Remove item from cart
    public function remove_from_cart($p_id, $ip_add, $c_id) {
        $p_id = mysqli_real_escape_string($this->db_conn(), $p_id);
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "DELETE FROM cart WHERE p_id = '$p_id' AND c_id = '$c_id'";
        } else {
            $sql = "DELETE FROM cart WHERE p_id = '$p_id' AND ip_add = '$ip_add' AND c_id IS NULL";
        }
        
        return $this->db_write_query($sql);
    }
    
    // Empty entire cart
    public function empty_cart($ip_add, $c_id) {
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "DELETE FROM cart WHERE c_id = '$c_id'";
        } else {
            $sql = "DELETE FROM cart WHERE ip_add = '$ip_add' AND c_id IS NULL";
        }
        
        return $this->db_write_query($sql);
    }
    
    // Get cart total
    public function get_cart_total($ip_add, $c_id) {
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "SELECT SUM(p.product_price * c.qty) as total
                    FROM cart c
                    JOIN products p ON c.p_id = p.product_id
                    WHERE c.c_id = '$c_id'";
        } else {
            $sql = "SELECT SUM(p.product_price * c.qty) as total
                    FROM cart c
                    JOIN products p ON c.p_id = p.product_id
                    WHERE c.ip_add = '$ip_add' AND c.c_id IS NULL";
        }
        
        $result = $this->db_fetch_one($sql);
        return $result ? $result['total'] : 0;
    }
    
    // Count cart items
    public function count_cart_items($ip_add, $c_id) {
        $ip_add = mysqli_real_escape_string($this->db_conn(), $ip_add);
        
        if ($c_id) {
            $c_id = mysqli_real_escape_string($this->db_conn(), $c_id);
            $sql = "SELECT SUM(qty) as total_items FROM cart WHERE c_id = '$c_id'";
        } else {
            $sql = "SELECT SUM(qty) as total_items FROM cart WHERE ip_add = '$ip_add' AND c_id IS NULL";
        }
        
        $result = $this->db_fetch_one($sql);
        return $result && $result['total_items'] ? $result['total_items'] : 0;
    }
}
?>