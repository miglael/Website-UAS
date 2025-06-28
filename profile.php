<?php
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$query = "SELECT * FROM data_user WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
        }

        .container {
            display: flex;
            margin: 50px auto;
            max-width: 800px;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            align-items: center;
        }

        .profile-table {
            flex: 2;
            border-collapse: collapse;
            width: 100%;
        }

        .profile-table td {
            padding: 10px 15px;
            vertical-align: top;
        }

        .profile-table td:first-child {
            font-weight: bold;
            width: 200px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image {
            flex: 1;
            text-align: center;
        }

        .profile-image img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ccc;
        }
    </style>
</head>

<body>

    <!-- Jika kamu punya navbar seperti main.php, bisa letakkan di sini -->

    <div class="container">
        <table class="profile-table">
            <tr>
                <td>NRP</td>
                <td><?= htmlspecialchars($data['nrp']) ?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><?= htmlspecialchars($data['nama']) ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?= htmlspecialchars($data['alamat']) ?></td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td><?= htmlspecialchars($data['ttl']) ?></td>
            </tr>
            <tr>
                <td>No HP</td>
                <td><?= htmlspecialchars($data['nohp']) ?></td>
            </tr>
            <tr>
                <td>email</td>
                <td><?= htmlspecialchars($data['email']) ?></td>
            </tr>
            <tr>
                <td>Level</td>
                <td><?= htmlspecialchars($data['level']) ?></td>
            </tr>
        </table>

        <div class="profile-image">
            <?php if ($data['level'] === 'dosen'): ?>
                <img src="dosen.png" alt="Foto Dosen">
            <?php else: ?>
                <img src="siswa.png" alt="Foto Siswa">
            <?php endif; ?>
        </div>

    </div>

</body>

</html>