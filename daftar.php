<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nrp = mysqli_real_escape_string($conn, $_POST['nrp']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $ttl = mysqli_real_escape_string($conn, $_POST['ttl']);
    $nohp = mysqli_real_escape_string($conn, $_POST['nohp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $level = mysqli_real_escape_string($conn, $_POST['level']);

    // Cek apakah NRP sudah terdaftar
    $cek_nrp = "SELECT * FROM data_user WHERE nrp = '$nrp'";
    $result = mysqli_query($conn, $cek_nrp);

    if (mysqli_num_rows($result) > 0) {
        // Jika NRP sudah ada, update data
        $sql = "UPDATE data_user SET 
                    nama = '$nama',
                    alamat = '$alamat',
                    ttl = '$ttl',
                    nohp = '$nohp',
                    email = '$email',
                    username = '$username',
                    password = '$password',
                    level = '$level'
                WHERE nrp = '$nrp'";
    } else {
        // Jika NRP belum ada, insert data baru
        $sql = "INSERT INTO data_user (nrp, nama, alamat, ttl, nohp, email, username, password, level)
                VALUES ('$nrp', '$nama', '$alamat', '$ttl', '$nohp', '$email', '$username', '$password', '$level')";
    }

    if (mysqli_query($conn, $sql)) {
        if ($level === 'siswa') {
            $tugas_result = mysqli_query($conn, "SELECT tugas_id FROM tugas");
            while ($tugas = mysqli_fetch_assoc($tugas_result)) {
                $tugas_id = $tugas['tugas_id'];
                $cek_exist = mysqli_query($conn, "SELECT * FROM data_pengumpulan WHERE nrp = '$nrp' AND tugas_id = '$tugas_id'");
                if (mysqli_num_rows($cek_exist) == 0) {
                    mysqli_query($conn, "INSERT INTO data_pengumpulan (tugas_id, nrp, isUploaded) VALUES ('$tugas_id', '$nrp', 0)");
                }
            }
        }

        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Halaman Pendaftaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url(pens.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            /* transparan sedikit */
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 480px;
            box-sizing: border-box;
        }

        h1 {
            color: #222;
            font-size: 24px;
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .link-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .link-login a {
            color: #007bff;
            text-decoration: none;
        }

        .link-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Daftar Akun Baru</h1>
        <form method="post">
            <label for="nrp">NRP</label>
            <input type="text" id="nrp" name="nrp" required>

            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" required>

            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" required>

            <label for="ttl">Tempat, Tanggal Lahir</label>
            <input type="text" id="ttl" name="ttl" placeholder="Contoh: Surabaya, 01-01-2000" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="nohp">Nomor HP</label>
            <input type="text" id="nohp" name="nohp" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="level">Level</label>
            <select id="level" name="level" required>
                <option value="">-- Pilih Level --</option>
                <option value="dosen">Dosen</option>
                <option value="siswa">Siswa</option>
            </select>

            <button type="submit" name="submit">Daftar</button>
        </form>
        <div class="link-login">
            <h4>Sudah punya akun?</h4>
            <a href="login.php">Login</a>
        </div>
    </div>

</body>

</html>