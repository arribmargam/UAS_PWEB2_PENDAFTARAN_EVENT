<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: ../view/login.php"); exit; }
require_once '../model/EventModel.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $eventModel = new EventModel();
    
    if ($eventModel->deleteEvent($id)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='../view/dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href='../view/dashboard.php';</script>";
    }
}
?>