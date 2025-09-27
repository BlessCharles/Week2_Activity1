<?php

require_once '../settings/db_class.php';

class Category extends db_connection
{
    public function __construct()
    {
        parent::db_connect();
    }


    public function addCategory($cat_name)
    {
        $cat_name = trim($cat_name);
        if ($cat_name === '' || strlen($cat_name) > 100) return false;

    
        $stmt = $this->db->prepare("SELECT cat_id FROM categories WHERE cat_name = ?");
        $stmt->bind_param("s", $cat_name);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return 'exists';
        }

        $stmt = $this->db->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param("s", $cat_name);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }


    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT cat_id, cat_name FROM categories ORDER BY cat_name ASC");
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return $rows;
    }

    
    public function updateCategory($cat_id, $cat_name)
    {
        $cat_name = trim($cat_name);
        if ($cat_name === '' || strlen($cat_name) > 100) return false;

        
        $stmt = $this->db->prepare("SELECT cat_id FROM categories WHERE cat_name = ? AND cat_id != ?");
        $stmt->bind_param("si", $cat_name, $cat_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return 'exists';
        }

        $stmt = $this->db->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param("si", $cat_name, $cat_id);
        if ($stmt->execute()) {
            return $stmt->affected_rows >= 0;
        }
        return false;
    }

    
    public function deleteCategory($cat_id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $cat_id);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }
}
