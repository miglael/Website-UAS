<?php
session_start();
include 'koneksi.php';

$tugas_id = $_POST['tugas_id'] ?? '';
$nrp = $_SESSION['nrp'] ?? '';
$uploadDir = 'file_upload/';

if (isset($_POST['submit'])) {
    $fileName = $_FILES['userfile']['name'];
    $tmpName = $_FILES['userfile']['tmp_name'];
    $fileSize = $_FILES['userfile']['size'];
    $fileType = mime_content_type($tmpName);
    $filePath = $uploadDir . basename($fileName);
    $fileNote = ($_POST['catatan'] == "") ? '-' : mysqli_real_escape_string($conn, $_POST['catatan']);

    // Validasi minimal
    if (!is_uploaded_file($tmpName)) {
        echo "Upload gagal.";
        exit();
    }

    if (move_uploaded_file($tmpName, $filePath)) {
        // Cek apakah sudah pernah mengumpulkan (jika belum, INSERT, jika sudah, UPDATE)
        $cek = mysqli_query($conn, "SELECT * FROM data_pengumpulan WHERE tugas_id = '$tugas_id' AND nrp = '$nrp'");
        if (mysqli_num_rows($cek) > 0) {
            // Sudah pernah, UPDATE
            $query = "UPDATE data_pengumpulan SET
                        file_name = '$fileName',
                        file_type = '$fileType',
                        file_size = '$fileSize',
                        file_path = '$filePath',
                        catatan = '$fileNote',
                        isUploaded = 1,
                        tgl_kumpul = CURDATE(),
                        wkt_kumpul = CURTIME()
                      WHERE tugas_id = '$tugas_id' AND nrp = '$nrp'";
        } else {
            // Belum pernah, INSERT
            $query = "INSERT INTO data_pengumpulan 
                        (tugas_id, nrp, file_name, file_type, file_size, file_path, catatan, tgl_kumpul, wkt_kumpul, isUploaded)
                      VALUES 
                        ('$tugas_id', '$nrp', '$fileName', '$fileType', '$fileSize', '$filePath', '$fileNote', CURDATE(), CURTIME(), 1)";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: siswa_dashboard.php?page=tugas&id=$tugas_id");
            exit();
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal memindahkan file.";
    }
}
?>