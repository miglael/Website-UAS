<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        td,
        th {
            padding: 12px;
            text-align: center;
        }

        thead {
            background-color: yellow;
            color: blue;
        }


        #tambah a {
            text-decoration: none;
            color: black;
            text-align: center;
            font-size: large;
            font-weight: bold;
            display: center;
        }

        a:hover {
            color: white;
        }

        td a {
            color: black;
            text-decoration: none;
        }

        .table_data {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: fit-content;
            margin: 40px auto;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="table_data">
        <table border="1">
            <thead>
                <tr>
                    <th>NRP</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tempat/tgl lahir</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th>Username</th>
                </tr>

            </thead>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM data_user WHERE level='siswa'");
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?= $row['nrp'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td><?= $row['ttl'] ?></td>
                    <td><?= $row['nohp'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['username'] ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>

</body>

</html>