<?php
session_start();
if (
    !isset($_SESSION['user_is_logged_in']) ||
    $_SESSION['user_is_logged_in'] !== true
) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url(pens.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }

        .navbar {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            height: 60px;
        }

        .navbar .left {
            font-size: 18px;
            font-weight: bold;
        }

        .navbar .right a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
        }

        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            height: 100vh;
            padding-top: 70px;
            /* Geser isi sidebar ke bawah agar tidak ketumpuk navbar */
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            margin-left: 200px;
            padding: 30px;
            margin-top: 70px;
            /* Supaya konten tidak tertutup navbar */
        }

        .welcome-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="left">Selamat Datang di Dashboard Dosen</div>
        <div class="right">
            <a href="dosen_dashboard.php?page=overview">Overview</a>
            <a href="dosen_dashboard.php?page=profile">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="sidebar">
        <a href="dosen_dashboard.php?page=overview">Overview</a>
        <a href="dosen_dashboard.php?page=profile">Profile</a>
        <a href="dosen_dashboard.php?page=data_mahasiswa">Data Mahasiswa</a>
        <a href="dosen_dashboard.php?page=tugas">Data Tugas</a>
    </div>

    <div class="main-content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'overview';
        $allowed_pages = [
            'profile',
            'data_mahasiswa',
            'tugas',
            'data_pengumpulan'
        ];

        if (in_array($page, $allowed_pages)) {
            include "$page.php";
        } else {
            ?>
            <div class="welcome-box">
                <h2>Selamat Datang di Dashboard Dosen</h2>
                <p>Ini adalah halaman utama untuk Dosen. Gunakan menu di sebelah kiri untuk navigasi.</p>
            </div>

        <?php } ?>
    </div>

</body>

</html>