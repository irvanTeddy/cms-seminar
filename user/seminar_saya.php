<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id'];

$data = mysqli_query($koneksi, "
SELECT
    pendaftaran.id_daftar,
    pendaftaran.status AS status_pendaftaran,

    seminar.id_seminar,
    seminar.judul,
    seminar.deskripsi,
    seminar.pemateri,
    seminar.tanggal,
    seminar.jam,
    seminar.lokasi,
    seminar.biaya,
    seminar.poster

FROM pendaftaran

JOIN seminar
ON pendaftaran.id_seminar = seminar.id_seminar

WHERE pendaftaran.id_user='$id_user'

ORDER BY pendaftaran.id_daftar DESC
");

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Seminar Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <h2 class="mb-4">

            Seminar Saya

        </h2>

        <?php

        if (mysqli_num_rows($data) == 0) {

        ?>

            <div class="alert alert-warning">

                Anda belum mendaftar seminar.

            </div>

        <?php

        }

        while ($d = mysqli_fetch_assoc($data)) {

        ?>

            <div class="card shadow mb-4">

                <div class="row g-0">

                    <div class="col-md-4">

                        <?php

                        if (!empty($d['poster'])) {

                        ?>

                            <img src="../uploads/poster/<?= $d['poster']; ?>"
                                class="img-fluid rounded-start"
                                style="height:100%;width:100%;object-fit:cover;">

                        <?php

                        } else {

                        ?>

                            <div class="bg-secondary text-white d-flex justify-content-center align-items-center"
                                style="height:320px;">

                                Poster Belum Tersedia

                            </div>

                        <?php } ?>

                    </div>

                    <div class="col-md-8">

                        <div class="card-body">

                            <h3 class="fw-bold">

                                <?= $d['judul']; ?>

                            </h3>

                            <hr>

                            <table class="table table-bordered">

                                <tr>

                                    <th width="180">Tanggal</th>

                                    <td><?= date('d-m-Y', strtotime($d['tanggal'])); ?></td>

                                </tr>

                                <tr>

                                    <th>Jam</th>

                                    <td><?= $d['jam']; ?> WIB</td>

                                </tr>

                                <tr>

                                    <th>Lokasi</th>

                                    <td><?= $d['lokasi']; ?></td>

                                </tr>

                                <tr>

                                    <th>Pemateri</th>

                                    <td><?= $d['pemateri']; ?></td>

                                </tr>

                                <tr>

                                    <th>Biaya</th>

                                    <td>

                                        Rp <?= number_format($d['biaya'], 0, ',', '.'); ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>Status Pendaftaran</th>

                                    <td>

                                        <?php

                                        if ($d['status_pendaftaran'] == "Pending") {

                                            echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                        } elseif ($d['status_pendaftaran'] == "Diverifikasi") {

                                            echo "<span class='badge bg-success'>Diverifikasi</span>";
                                        } elseif ($d['status_pendaftaran'] == "Ditolak") {

                                            echo "<span class='badge bg-danger'>Ditolak</span>";
                                        } else {

                                            echo "<span class='badge bg-secondary'>" . $d['status_pendaftaran'] . "</span>";
                                        }

                                        ?>

                                    </td>

                                </tr>

                            </table>

                            <h5 class="mt-3">

                                Deskripsi Seminar

                            </h5>

                            <p>

                                <?= nl2br($d['deskripsi']); ?>

                            </p>
                            <?php
                            if ($d['status_pendaftaran'] == "Diverifikasi") {
                            ?>

                                <a
                                    href="cetak_bukti.php?id=<?= $d['id_daftar']; ?>"
                                    target="_blank"
                                    class="btn btn-success mt-3">

                                    📄 Download Bukti PDF

                                </a>

                            <?php
                            }
                            ?>


                        </div>

                    </div>

                </div>

            </div>


        <?php } ?>

        <a href="dashboard.php" class="btn btn-secondary">

            ← Kembali

        </a>

    </div>

</body>

</html>