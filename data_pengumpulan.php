<?php
include 'koneksi.php';

// Cek apakah user adalah dosen
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'dosen') {
    echo "Akses ditolak.";
    exit;
}

$tugas_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data tugas
$tugas_query = mysqli_query($conn, "SELECT * FROM tugas WHERE tugas_id = $tugas_id");
$tugas = mysqli_fetch_assoc($tugas_query);

echo "<h2>Data Pengumpulan - " . htmlspecialchars($tugas['nama_tugas']) . "</h2>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    table thead {
        background-color: #007bff;
        color: white;
    }

    table th,
    table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }


    form {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    input[type="text"] {
        padding: 6px 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 60px;
    }

    button {
        padding: 6px 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }
</style>


<body>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>NRP</th>
                <th>File</th>
                <th>Catatan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM data_pengumpulan WHERE tugas_id = $tugas_id ORDER BY nrp ASC");
            while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['nrp']); ?></td>
                    <td>
                        <a href="download.php?id=<?php echo $row['tugas_id']; ?>&nrp=<?php echo $row['nrp']; ?>"
                            target="_blank">
                            <?= htmlspecialchars($row['file_name']); ?>
                        </a>
                    </td>
                    <td><?= nl2br(htmlspecialchars($row['catatan'])); ?></td>
                    <td><?= $row['tgl_kumpul']; ?></td>
                    <td><?= $row['wkt_kumpul']; ?></td>
                    <td><?= $row['nilai'] ?? '-'; ?></td>
                    <td>
                        <form method="POST" action="beri_nilai.php">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <input type="text" name="nilai" value="<?= htmlspecialchars($row['nilai']); ?>" size="5">
                            <button type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>

</html>