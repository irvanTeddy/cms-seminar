<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$query = mysqli_query($koneksi, "
SELECT
seminar.*,
panitia.nama_panitia,
kategori.nama_kategori

FROM seminar

LEFT JOIN panitia
ON seminar.id_panitia=panitia.id_panitia

LEFT JOIN kategori
ON seminar.id_kategori=kategori.id_kategori

WHERE seminar.status='Publish'

ORDER BY seminar.tanggal ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Seminar Informatika</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand">

                Seminar Informatika

            </a>

            <div>

                <?= $_SESSION['nama']; ?>

                |

                <a href="../logout.php" class="text-white">

                    Logout

                </a>

            </div>

        </div>

    </nav>

    <div class="container mt-4">

        <h3>Seminar Tersedia</h3>

        <div class="row">

            <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                <div class="col-md-4 mb-4">

                    <div class="card shadow h-100">

                        <?php

                        if ($row['poster'] != "") {

                        ?>

                            <img
                                src="../uploads/poster/<?= $row['poster']; ?>"
                                class="card-img-top"
                                style="height:220px;object-fit:cover;">

                        <?php } ?>

                        <div class="card-body">

                            <h5>

                                <?= $row['judul']; ?>

                            </h5>

                            <p>

                                <b>Panitia</b>

                                <br>

                                <?= $row['nama_panitia']; ?>

                            </p>

                            <p>

                                <?= $row['nama_kategori']; ?>

                            </p>

                            <p>

                                <?= date('d-m-Y', strtotime($row['tanggal'])); ?>

                            </p>

                            <p>

                                Rp <?= number_format($row['biaya']); ?>

                            </p>

                            <a
                                href="daftar.php?id=<?= $row['id_seminar']; ?>"
                                class="btn btn-primary w-100">

                                Daftar Seminar

                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>


        </div>
        <a href="dashboard.php" class="btn btn-secondary">

            ← Kembali

        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>