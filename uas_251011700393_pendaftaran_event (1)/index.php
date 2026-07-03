<?php
session_start();
// Jika sudah login, langsung ke dashboard. Jika belum, arahkan ke form login.
if (isset($_SESSION['username'])) {
    header("Location: view/dashboard.php");
} else {
    header("Location: view/login.php");
}
exit;
?>