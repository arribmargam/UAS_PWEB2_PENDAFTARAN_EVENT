<?php
require_once '../model/EventModel.php';

class EventController {
    private $model;

    public function __construct() {
        $this->model = new EventModel();
    }

    public function handleUploadAndInsert($postData, $fileData) {
        // Validasi Data Input Dasar
        if(empty($postData['id']) || empty($postData['nama_peserta']) || empty($postData['nama_event'])) {
            return "Semua kolom input teks wajib diisi!";
        }

        // Validasi dan Upload File
        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileName = $fileData['file_bukti']['name'];
        $fileTmp  = $fileData['file_bukti']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExt)) {
            return "Format file tidak didukung! Gunakan JPG, PNG, atau PDF.";
        }

        // Generate nama file unik agar tidak bentrok
        $newFileName = time() . "_" . $fileName;
        $uploadPath = "../uploads/" . $newFileName;

        if (move_uploaded_file($fileTmp, $uploadPath)) {
            // Jika berhasil upload, masukan data ke database melalui Model
            $this->model->addEvent($postData['id'], $postData['nama_peserta'], $postData['nama_event'], date('Y-m-d'), $newFileName);
            return "Data berhasil ditambahkan!";
        } else {
            return "Gagal mengunggah file bukti pendaftaran.";
        }
    }
}
?>