<?php
/**
 * Database Configuration and Connection
 */

class Database {
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct() {
        // Get from environment or use defaults
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->database = getenv('DB_NAME') ?: 'urbansync';
    }

    public function connect() {
        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            // Check connection
            if ($this->conn->connect_error) {
                throw new Exception('Database connection failed: ' . $this->conn->connect_error);
            }

            // Set charset to utf8mb4
            $this->conn->set_charset('utf8mb4');

            return $this->conn;
        } catch (Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            die('Unable to connect to database. Please try again later.');
        }
    }

    public function getConnection() {
        if ($this->conn === null) {
            $this->connect();
        }
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Global database instance
$db = new Database();
$connection = $db->connect();
