<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: ../view/login.php"); exit; }
require_once '../model/EventModel.php';

$eventModel = new EventModel();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ========== TAMBAH DATA (CREATE) ==========
    if (isset($_POST['id']) && !isset($_POST['edit_id'])) { 
        $id = $_POST['id'];
        $nama_peserta = $_POST['nama_peserta'];
        $nama_event = $_POST['nama_event'];
        $tanggal = date('Y-m-d');
        
        // Validasi file
        $file_name = $_FILES['file_bukti']['name'];
        $file_tmp = $_FILES['file_bukti']['tmp_name'];
        $file_error = $_FILES['file_bukti']['error'];
        
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');
        
        // Validasi ekstensi
        if (!in_array($file_ext, $allowed)) {
            echo "<script>alert('Ekstensi file tidak diizinkan! Hanya JPG, PNG, PDF.'); window.location.href='../view/tambah_event.php';</script>";
            exit;
        }
        
        // Validasi error upload
        if ($file_error !== 0) {
            echo "<script>alert('Gagal upload file! Kode error: $file_error'); window.location.href='../view/tambah_event.php';</script>";
            exit;
        }
        
        // Buat folder uploads jika belum ada
        if (!is_dir('../uploads')) { 
            mkdir('../uploads', 0777, true); 
        }
        
        // Generate nama file unik
        $file_destination = '../uploads/' . time() . '_' . uniqid() . '.' . $file_ext;
        
        // Pindahkan file
        if (move_uploaded_file($file_tmp, $file_destination)) {
            if ($eventModel->addEvent($id, $nama_peserta, $nama_event, $tanggal, $file_destination)) {
                echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='../view/dashboard.php';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan data!'); window.location.href='../view/tambah_event.php';</script>";
            }
        } else {
            echo "<script>alert('Gagal memindahkan file!'); window.location.href='../view/tambah_event.php';</script>";
        }
    } 
    
    // ========== UBAH DATA (UPDATE) ==========
    elseif (isset($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $nama_peserta = $_POST['nama_peserta'];
        $nama_event = $_POST['nama_event'];
        $status = $_POST['status'];

        if ($eventModel->updateEvent($id, $nama_peserta, $nama_event, $status)) {
            echo "<script>alert('Data berhasil diubah!'); window.location.href='../view/dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal mengubah data!'); window.location.href='../view/edit_event.php?id=$id';</script>";
        }
    }
}
?>