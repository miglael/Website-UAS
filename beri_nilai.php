<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level'] !== 'dosen') {
    echo "Akses ditolak.";
    exit;
}

$id = intval($_POST['id']);
$nilai = mysqli_real_escape_string($conn, $_POST['nilai']);

$query = "UPDATE data_pengumpulan SET nilai = '$nilai' WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    echo "Gagal menyimpan nilai.";
}
