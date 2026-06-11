<?php
/**
 * Product Model
 */

class Product {
    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($trader_id, $category_id, $name, $description, $price, $quantity, $image_url) {
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (trader_id, category_id, name, description, price, quantity, image_url) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param('iissdssi', $trader_id, $category_id, $name, $description, $price, $quantity, $image_url);
        return $stmt->execute();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, u.name as trader_name, u.email, u.phone, u.profile_image_url, c.name as category_name
             FROM {$this->table} p
             JOIN users u ON p.trader_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByTrader($trader_id, $limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE trader_id = ? LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('iii', $trader_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getByCategory($category_id, $limit = 12, $offset = 0) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE category_id = ? AND status = 'active' LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('iii', $category_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function search($query, $category_id = null, $min_price = null, $max_price = null, $limit = 12, $offset = 0) {
        $search_query = "SELECT * FROM {$this->table} WHERE status = 'active' AND (name LIKE ? OR description LIKE ?)";
        $params = ['%' . $query . '%', '%' . $query . '%'];
        $types = 'ss';

        if ($category_id) {
            $search_query .= " AND category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }
        if ($min_price) {
            $search_query .= " AND price >= ?";
            $params[] = $min_price;
            $types .= 'd';
        }
        if ($max_price) {
            $search_query .= " AND price <= ?";
            $params[] = $max_price;
            $types .= 'd';
        }

        $search_query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->conn->prepare($search_query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $name, $description, $price, $quantity) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET name = ?, description = ?, price = ?, quantity = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param('ssdsii', $name, $description, $price, $quantity, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function incrementView($id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getFeatured($limit = 8) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE featured = TRUE AND status = 'active' ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPopular($limit = 8) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY views DESC LIMIT ?"
        );
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
