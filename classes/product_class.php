<?php

require_once '../settings/db_class.php';

class Product extends db_connection {
    public function __construct()
    {
        parent::db_connect();
    }

    /**
     * Add a new product
     */
    public function addProduct($cat_id, $brand_id, $title, $price, $desc, $image, $keywords)
    {
        $title = trim($title);
        if ($title === '' || $price <= 0) return false;

        $stmt = $this->db->prepare("INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisdsss", $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    /**
     * Get all products with category and brand info
     */
    public function getAllProducts()
    {
        $stmt = $this->db->prepare("
            SELECT p.product_id, p.product_title, p.product_price, p.product_desc,
                   p.product_image, p.product_keywords,
                   c.cat_name, b.brand_name, p.product_cat, p.product_brand
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            ORDER BY p.product_id DESC
        ");
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return $rows;
    }

    /**
     * Get single product by ID
     */
    public function getProductById($product_id)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            WHERE p.product_id = ?
        ");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Update a product
     */
    public function updateProduct($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords)
    {
        $title = trim($title);
        if ($title === '' || $price <= 0) return false;

        // If image is empty, don't update it
        if ($image) {
            $stmt = $this->db->prepare("UPDATE products SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_image=?, product_keywords=? WHERE product_id=?");
            $stmt->bind_param("iisdsssi", $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $product_id);
        } else {
            $stmt = $this->db->prepare("UPDATE products SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_keywords=? WHERE product_id=?");
            $stmt->bind_param("iisdssi", $cat_id, $brand_id, $title, $price, $desc, $keywords, $product_id);
        }

        if ($stmt->execute()) {
            return $stmt->affected_rows >= 0;
        }
        return false;
    }

    /**
     * Delete a product
     */
    public function deleteProduct($product_id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }
}