<?php

require_once '../settings/db_class.php';

class Brand extends db_connection {
    public function __construct()
    {
        parent::db_connect();
    }

    /**
     * Add a new brand
     */
    public function addBrand($brand_name, $cat_id)
    {
        $brand_name = trim($brand_name);
        if ($brand_name === '' || strlen($brand_name) > 100) return false;

        // Check if brand name already exists
        $stmt = $this->db->prepare("SELECT brand_id FROM brands WHERE brand_name = ?");
        $stmt->bind_param("s", $brand_name);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return 'exists';
        }

        // Insert new brand with category
        $stmt = $this->db->prepare("INSERT INTO brands (brand_name, brand_cat) VALUES (?, ?)");
        $stmt->bind_param("si", $brand_name, $cat_id);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    /**
     * Get all brands with their categories
     */
    public function getAllBrands()
    {
        $stmt = $this->db->prepare("
            SELECT b.brand_id, b.brand_name, b.brand_cat, c.cat_name
            FROM brands b
            LEFT JOIN categories c ON b.brand_cat = c.cat_id
            ORDER BY c.cat_name ASC, b.brand_name ASC
        ");
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return $rows;
    }

    /**
     * Update a brand
     */
    public function updateBrand($brand_id, $brand_name)
    {
        $brand_name = trim($brand_name);
        if ($brand_name === '' || strlen($brand_name) > 100) return false;

        // Check if another brand has the same name
        $stmt = $this->db->prepare("SELECT brand_id FROM brands WHERE brand_name = ? AND brand_id != ?");
        $stmt->bind_param("si", $brand_name, $brand_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return 'exists';
        }

        // Update brand
        $stmt = $this->db->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->bind_param("si", $brand_name, $brand_id);
        if ($stmt->execute()) {
            return $stmt->affected_rows >= 0;
        }
        return false;
    }

    /**
     * Delete a brand
     */
    public function deleteBrand($brand_id)
    {
        $stmt = $this->db->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }
}