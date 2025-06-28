<?php
if (isset($_GET['id'])) {
    include 'koneksi.php';

    $tugas_id = $_GET['id'];
    $nrp = isset($_GET['nrp']) ? $_GET['nrp'] : $_SESSION['nrp'];

    $query = "SELECT file_name, file_type, file_size, file_path FROM data_pengumpulan WHERE tugas_id = '$tugas_id' AND nrp = '$nrp'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        list($name, $type, $size, $path) = mysqli_fetch_array($result);

        if (file_exists($path)) {
            header("Content-Disposition: attachment; filename=" . basename($name));
            header("Content-Length: " . filesize($path));
            header("Content-Type: $type");

            readfile($path);
            header("Location: index.php?page=detail_tugas&id=$tugas_id");
            exit();
        } else {
            echo "File tidak ditemukan.";
        }
    } else {
        echo "Data tidak ditemukan di database.";
    }
}
?>