<?php
// Pastikan session hanya dimulai sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah ada session login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../index.php");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "db_laundry");

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
