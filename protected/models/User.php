<?php
/**
 * User Model
 */

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $email, $password, $role = 'student', $phone = null) {
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (name, email, password_hash, role, phone) 
             VALUES (?, ?, ?, ?, ?)"
        );
        
        $password_hash = Helper::hashPassword($password);
        $stmt->bind_param('sssss', $name, $email, $password_hash, $role, $phone);
        
        return $stmt->execute();
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $name, $phone = null, $bio = null) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET name = ?, phone = ?, bio = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param('sssi', $name, $phone, $bio, $id);
        return $stmt->execute();
    }

    public function updateProfileImage($id, $image_url) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET profile_image_url = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param('si', $image_url, $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $new_password) {
        $password_hash = Helper::hashPassword($new_password);
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET password_hash = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param('si', $password_hash, $id);
        return $stmt->execute();
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getByRole($role) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE role = ?");
        $stmt->bind_param('s', $role);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM {$this->table}");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
