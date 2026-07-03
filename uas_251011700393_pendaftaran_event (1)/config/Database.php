<?php

class Database {
    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $dbname = 'uas_pendaftaran_event';
    private $username = 'root';
    private $password = '';
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch(PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
    
    private function __clone() {}
    private function __wakeup() {}
    
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->conn;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}

// Cara pakai:
$db = Database::getInstance();
$users = $db->query("SELECT * FROM users WHERE status = ?", ['active']);