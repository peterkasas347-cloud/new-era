<?php
/**
 * House Model
 */

class House {
    private $conn;
    private $table = 'houses';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($owner_id, $title, $description, $price, $address, $city, $latitude, $longitude, $bedrooms, $bathrooms, $image_url) {
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (owner_id, title, description, price, address, city, latitude, longitude, bedrooms, bathrooms, image_url) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param('issdssddiis', $owner_id, $title, $description, $price, $address, $city, $latitude, $longitude, $bedrooms, $bathrooms, $image_url);
        return $stmt->execute();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare(
            "SELECT h.*, u.name as owner_name, u.email, u.phone, u.profile_image_url 
             FROM {$this->table} h 
             JOIN users u ON h.owner_id = u.id 
             WHERE h.id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByOwner($owner_id, $limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE owner_id = ? LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('iii', $owner_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function search($city = null, $min_price = null, $max_price = null, $bedrooms = null, $limit = 12, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE status = 'available'";
        $params = [];
        $types = '';

        if ($city) {
            $query .= " AND city LIKE ?";
            $params[] = '%' . $city . '%';
            $types .= 's';
        }
        if ($min_price) {
            $query .= " AND price >= ?";
            $params[] = $min_price;
            $types .= 'd';
        }
        if ($max_price) {
            $query .= " AND price <= ?";
            $params[] = $max_price;
            $types .= 'd';
        }
        if ($bedrooms) {
            $query .= " AND bedrooms = ?";
            $params[] = $bedrooms;
            $types .= 'i';
        }

        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $title, $description, $price, $bedrooms, $bathrooms) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET title = ?, description = ?, price = ?, bedrooms = ?, bathrooms = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param('ssdiii', $title, $description, $price, $bedrooms, $bathrooms, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getNearby($latitude, $longitude, $radius = 5) {
        // Simple distance calculation (you might want to use ST_Distance for accuracy)
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} 
             WHERE (latitude BETWEEN ? AND ?) AND (longitude BETWEEN ? AND ?)
             AND status = 'available'"
        );
        $lat_min = $latitude - $radius;
        $lat_max = $latitude + $radius;
        $lng_min = $longitude - $radius;
        $lng_max = $longitude + $radius;
        $stmt->bind_param('dddd', $lat_min, $lat_max, $lng_min, $lng_max);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function incrementView($id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getFeatured($limit = 6) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE featured = TRUE AND status = 'available' ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
