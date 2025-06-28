<?php
include 'koneksi.php';

$level = $_SESSION['level'] ?? '';
$nrp = $_SESSION['nrp'] ?? '';

$query = "SELECT * FROM tugas ORDER BY tugas_id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: fit-content;
            overflow-x: auto;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            background: #f7f7f7;
        }

        .card h3 {
            margin-top: 0;
        }

        .card p {
            margin: 5px 0;
        }

        .form-upload {
            margin-top: 10px;
        }

        .form-upload input[type="file"],
        .form-upload textarea {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }

        .form-upload button {
            padding: 6px 12px;
            background: #1E3A8A;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-upload button:hover {
            background: #0d47a1;
        }

        .btn-pengumpulan {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #1E3A8A;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-pengumpulan:hover {
            background-color: #0d47a1;
        }

        .uploaded {
            background-color: #e6ffed;
            padding: 10px;
            margin-top: 10px;
            border-left: 5px solid #2ecc71;
        }

        .form-tambah {
            background: #e0f0ff;
            border: 1px solid #90c8f0;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-tambah h3 {
            margin-top: 0;
            color: #1E3A8A;
        }

        .form-tambah input[type="text"],
        .form-tambah input[type="date"],
        .form-tambah textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .form-tambah button[type="submit"] {
            background-color: #1E3A8A;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-tambah button[type="submit"]:hover {
            background-color: #0d47a1;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Tugas</h2>

        <?php if ($level === 'dosen'): ?>
            <div class="form-tambah">
                <h3>Tambah Tugas Baru</h3>
                <form method="post">
                    <label for="nama_tugas">Nama Tugas</label>
                    <input type="text" id="nama_tugas" name="nama_tugas" placeholder="Contoh: Tugas 1 Pemrograman" required>

                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi tugas..." required></textarea>

                    <label for="dl">Deadline</label>
                    <input type="date" id="dl" name="dl" required>

                    <button type="submit" name="tambah_tugas">Tambah Tugas</button>
                </form>
            </div>

            <?php
            if (isset($_POST['tambah_tugas'])) {
                $nama_tugas = mysqli_real_escape_string($conn, $_POST['nama_tugas']);
                $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                $dl = mysqli_real_escape_string($conn, $_POST['dl']);

                $insert = mysqli_query($conn, "INSERT INTO tugas (nama_tugas, deskripsi, dl) VALUES ('$nama_tugas', '$deskripsi', '$dl')");

                if ($insert) {
                    $tugas_id_baru = mysqli_insert_id($conn);
                    $q_siswa = mysqli_query($conn, "SELECT nrp FROM data_user WHERE level = 'siswa'");
                    while ($siswa = mysqli_fetch_assoc($q_siswa)) {
                        $nrp_siswa = $siswa['nrp'];
                        mysqli_query($conn, "INSERT INTO data_pengumpulan (tugas_id, nrp, isUploaded) VALUES ('$tugas_id_baru', '$nrp_siswa', 0)");
                    }

                    echo "<script>alert('Tugas berhasil ditambahkan!'); window.location.href = window.location.href;</script>";
                } else {
                    echo "<script>alert('Gagal menambahkan tugas.');</script>";
                }
            }
            ?>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $tugas_id = $row['tugas_id'];

            // Cek status upload untuk mahasiswa
            $uploaded = false;
            $data_pengumpulan = null;

            if ($level === 'siswa') {
                $cek = mysqli_query($conn, "SELECT * FROM data_pengumpulan WHERE tugas_id = $tugas_id AND nrp = '$nrp'");
                $data_pengumpulan = mysqli_fetch_assoc($cek);
                $uploaded = $data_pengumpulan && $data_pengumpulan['isUploaded'] == 1;
            }
            ?>

            <div class="card">
                <h3><?= htmlspecialchars($row['nama_tugas']) ?></h3>
                <p><strong>Deadline:</strong> <?= htmlspecialchars($row['dl']) ?></p>
                <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>

                <?php if ($level === 'dosen'): ?>
                    <a class="btn-pengumpulan" href="dosen_dashboard.php?page=data_pengumpulan&id=<?= $tugas_id ?>">Lihat
                        Pengumpulan</a>
                <?php else: ?>
                    <?php if ($uploaded): ?>
                        <div class="uploaded">
                            <strong>Sudah Mengumpulkan</strong><br>
                            File: <?= htmlspecialchars($data_pengumpulan['file_name']) ?><br>
                            Catatan: <?= nl2br(htmlspecialchars($data_pengumpulan['catatan'])) ?><br>
                            Tanggal: <?= $data_pengumpulan['tgl_kumpul'] ?>             <?= $data_pengumpulan['wkt_kumpul'] ?><br>
                            Nilai : <?= $data_pengumpulan['nilai'] ?>
                        </div>
                    <?php else: ?>
                        <form class="form-upload" action="upload_tugas.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="tugas_id" value="<?= $tugas_id ?>">
                            <input type="file" name="userfile" required>
                            <textarea name="catatan" rows="3" placeholder="Catatan (opsional)"></textarea>
                            <button type="submit" name="submit">Kumpulkan</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>