<?php
require_once '../config/Database.php';

class EventModel extends Database {
    
    // READ + SEARCH
    public function getEvents($keyword = "") {
        if(!empty($keyword)) {
            $stmt = $this->conn->prepare("SELECT * FROM pendaftaran_event WHERE nama_peserta LIKE ? OR nama_event LIKE ? ORDER BY id ASC");
            $stmt->execute(["%$keyword%", "%$keyword%"]);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM pendaftaran_event ORDER BY id ASC");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function addEvent($id, $nama_peserta, $nama_event, $tanggal, $file_bukti) {
        $stmt = $this->conn->prepare("INSERT INTO pendaftaran_event (id, nama_peserta, nama_event, tanggal_daftar, file_bukti, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$id, $nama_peserta, $nama_event, $tanggal, $file_bukti, 'pending']);
    }

    // DELETE
    public function deleteEvent($id) {
        $stmt = $this->conn->prepare("DELETE FROM pendaftaran_event WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // GET SINGLE DATA
    public function getEventById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pendaftaran_event WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function updateEvent($id, $nama_peserta, $nama_event, $status) {
        $stmt = $this->conn->prepare("UPDATE pendaftaran_event SET nama_peserta = ?, nama_event = ?, status = ? WHERE id = ?");
        return $stmt->execute([$nama_peserta, $nama_event, $status, $id]);
    }
}
?>