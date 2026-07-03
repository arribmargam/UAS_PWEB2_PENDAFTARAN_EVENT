<?php
class Database {
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=uas_pendaftaran_event", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
}
