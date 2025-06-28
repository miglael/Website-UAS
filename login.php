<?php
session_start();
include 'koneksi.php'; // Pastikan file ini berisi koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Tentukan level berdasarkan tombol yang diklik
    $level = isset($_POST['siswa']) ? 'siswa' : (isset($_POST['dosen']) ? 'dosen' : '');

    if ($level) {
        // Cek ke database
        $query = "SELECT * FROM data_user WHERE username=? AND level=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $level);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_is_logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            $_SESSION['nrp'] = $user['nrp'];

            if ($user['level'] == 'siswa') {
                header("Location: siswa_dashboard.php");
            } else {
                header("Location: dosen_dashboard.php");
                exit;
            }
            exit;
        } else {
            echo "<script>alert('Username atau password salah!');</script>";
        }
    }
}
?>


<style>
    body {
        /* Flexbox for centering content */
        display: flex;
        justify-content: center;
        /* Horizontally center */
        align-items: center;
        /* Vertically center */
        min-height: 100vh;
        /* Full viewport height */
        margin: 0;
        background-image: url(pens.jpg);
        /* Light background for contrast */
        font-family: Arial, sans-serif;
    }

    .login-container {
        background-color: #fff;
        padding: 32px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        text-align: center;
        width: 100%;
        max-width: 360px;
        box-sizing: border-box;
    }

    h1 {
        color: #333;
        font-size: 20px;
        margin-bottom: 12px;
    }

    label {
        display: block;
        text-align: left;
        margin-bottom: 6px;
        color: #444;
        font-weight: 600;
        font-size: 14px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        transition: border 0.2s ease;
    }

    input:focus {
        border-color: #007bff;
        outline: none;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 15px;
        width: 100%;
        margin-top: 5px;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    .link-daftar {
        margin-top: 20px;
        font-size: 14px;
    }

    .link-daftar a {
        text-decoration: none;
        color: #007bff;
    }

    .link-daftar a:hover {
        text-decoration: underline;
    }
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="login-container">
        <h1>Selamat datang di E-thol (Kw)</h1>
        <h1>Silahkan Login</h1>
        <form method="post">
            <label for="username">Username</label>
            <input type="text" name="username" required><br>

            <label for="password">Password</label>
            <input type="password" name="password" required><br><br>

            <button type="submit" name="siswa">login sebagai siswa</button>
            <br><br>
            <button type="submit" name="dosen">login sebagai dosen</button>
        </form>
        <div class="link-daftar">
            <h4>Belum punya akun?</h4>
            <a href="daftar.php">Daftar</a>
        </div>
    </div>

</body>

</html>