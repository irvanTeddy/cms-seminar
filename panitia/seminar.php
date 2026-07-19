<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

/* ===========================
   HAPUS SEMINAR
=========================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "
    DELETE FROM seminar
    WHERE id_seminar='$id'
    AND id_panitia='$id_panitia'
    ");

    header("Location: seminar.php");
    exit;
}

/* ===========================
   DATA SEMINAR
=========================== */

$data = mysqli_query($koneksi, "
SELECT
seminar.*,
kategori.nama_kategori
FROM seminar

LEFT JOIN kategori
ON seminar.id_kategori=kategori.id_kategori

WHERE seminar.id_panitia='$id_panitia'

ORDER BY seminar.id_seminar DESC
");
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Seminar Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand fw-bold">
                Panel Panitia
            </a>


            <div class="text-white">

                <?= $_SESSION['nama']; ?>

                |

                <a href="../logout.php" class="text-white text-decoration-none">

                    Logout

                </a>

            </div>

        </div>

    </nav>

    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">

            <h3>Seminar Saya</h3>

            <a href="tambah_seminar.php" class="btn btn-success">

                <i class="bi bi-plus-circle"></i>

                Tambah Seminar

            </a>

        </div>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>

                            <th>Poster</th>

                            <th>Judul</th>

                            <th>Kategori</th>

                            <th>Tanggal</th>

                            <th>Biaya</th>

                            <th>Status</th>

                            <th width="240">

                                Aksi

                            </th>

                        </tr>


                    </thead>


                    <tbody>

                        <?php

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($data)) {

                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td width="120">

                                    <?php

                                    if ($row['poster'] != "") {

                                    ?>

                                        <img
                                            src="../uploads/poster/<?= $row['poster']; ?>"
                                            width="100">

                                    <?php

                                    } else {

                                        echo "-";
                                    }

                                    ?>

                                </td>

                                <td>

                                    <?= $row['judul']; ?>

                                </td>

                                <td>

                                    <?= $row['nama_kategori']; ?>

                                </td>

                                <td>

                                    <?= date('d-m-Y', strtotime($row['tanggal'])); ?>

                                </td>

                                <td>

                                    Rp <?= number_format($row['biaya']); ?>

                                </td>

                                <td>

                                    <?php

                                    if ($row['status'] == "Draft") {

                                        echo "<span class='badge bg-secondary'>Draft</span>";
                                    } elseif ($row['status'] == "Publish") {

                                        echo "<span class='badge bg-success'>Publish</span>";
                                    } else {

                                        echo "<span class='badge bg-warning text-dark'>Selesai</span>";
                                    }

                                    ?>

                                </td>

                                <td>

                                    <a
                                        href="edit_seminar.php?id=<?= $row['id_seminar']; ?>"
                                        class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <a
                                        href="peserta.php?id=<?= $row['id_seminar']; ?>"
                                        class="btn btn-primary btn-sm">

                                        <i class="bi bi-people"></i>

                                    </a>

                                    <a
                                        onclick="return confirm('Hapus seminar?')"
                                        href="?hapus=<?= $row['id_seminar']; ?>"
                                        class="btn btn-danger btn-sm">

                                        <i class="bi bi-trash"></i>

                                    </a>

                                </td>

                            </tr>

                        <?php

                        }

                        ?>

                    </tbody>

                </table>
                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>

            </div>

        </div>

    </div>

</body>

</html>