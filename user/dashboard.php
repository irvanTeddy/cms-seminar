<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id'];

// Total seminar yang diikuti
$q = mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE id_user='$id_user'
");

$total = mysqli_fetch_assoc($q)['total'];

// Jumlah yang sudah diverifikasi
$q2 = mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE id_user='$id_user'
AND status='Diverifikasi'
");

$verif = mysqli_fetch_assoc($q2)['total'];

// Jumlah pending
$q3 = mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE id_user='$id_user'
AND status='Pending'
");

$pending = mysqli_fetch_assoc($q3)['total'];

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand fw-bold">

                CMS Seminar Informatika

            </a>

            <div class="text-white">

                Halo,

                <b><?= $_SESSION['nama']; ?></b>

                |

                <a href="../logout.php" class="text-white text-decoration-none">

                    Logout

                </a>

            </div>

        </div>

    </nav>

    <div class="container mt-4">

        <h3 class="mb-4">

            Dashboard Peserta

        </h3>

        <div class="row">

            <div class="col-md-4">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-journal-bookmark display-4 text-primary"></i>

                        <h2><?= $total; ?></h2>

                        <h5>Seminar Diikuti</h5>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-check-circle display-4 text-success"></i>

                        <h2><?= $verif; ?></h2>

                        <h5>Sudah Diverifikasi</h5>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-clock-history display-4 text-warning"></i>

                        <h2><?= $pending; ?></h2>

                        <h5>Menunggu Verifikasi</h5>

                    </div>

                </div>

            </div>

        </div>

        <hr class="my-5">

        <h4>Menu</h4>

        <div class="row mt-3">

            <div class="col-md-4">

                <a href="seminar.php" class="btn btn-primary w-100 p-3">

                    <i class="bi bi-search fs-2"></i>

                    <br>

                    Lihat Seminar

                </a>

            </div>

            <div class="col-md-4">

                <a href="seminar_saya.php" class="btn btn-success w-100 p-3">

                    <i class="bi bi-card-checklist fs-2"></i>

                    <br>

                    Seminar Saya

                </a>

            </div>

            <div class="col-md-4">

                <a href="../logout.php" class="btn btn-danger w-100 p-3">

                    <i class="bi bi-box-arrow-right fs-2"></i>

                    <br>

                    Logout

                </a>

            </div>

        </div>

        <hr class="my-5">

        <h4>Riwayat Pendaftaran</h4>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead class="table-dark">

                        <tr>

                            <th>Judul Seminar</th>

                            <th>Tanggal</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $data = mysqli_query($koneksi, "
SELECT seminar.judul,
seminar.tanggal,
pendaftaran.status
FROM pendaftaran
JOIN seminar
ON pendaftaran.id_seminar=seminar.id_seminar
WHERE pendaftaran.id_user='$id_user'
ORDER BY pendaftaran.id_daftar DESC
");

                        if (mysqli_num_rows($data) == 0) {

                            echo "<tr><td colspan='3' class='text-center'>Belum ada pendaftaran.</td></tr>";
                        } else {

                            while ($d = mysqli_fetch_assoc($data)) {

                                echo "<tr>";

                                echo "<td>" . $d['judul'] . "</td>";

                                echo "<td>" . date('d-m-Y', strtotime($d['tanggal'])) . "</td>";

                                echo "<td>";

                                if ($d['status'] == "Pending") {

                                    echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                } elseif ($d['status'] == "Diverifikasi") {

                                    echo "<span class='badge bg-success'>Diverifikasi</span>";
                                } else {

                                    echo "<span class='badge bg-danger'>Ditolak</span>";
                                }

                                echo "</td>";

                                echo "</tr>";
                            }
                        }

                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>