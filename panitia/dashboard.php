<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

// Hitung seminar
$qSeminar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM seminar WHERE id_panitia='$id_panitia'");
$totalSeminar = mysqli_fetch_assoc($qSeminar)['total'];

// Hitung peserta
$qPeserta = mysqli_query($koneksi, "
SELECT COUNT(*) as total
FROM pendaftaran
JOIN seminar ON pendaftaran.id_seminar=seminar.id_seminar
WHERE seminar.id_panitia='$id_panitia'
");

$totalPeserta = mysqli_fetch_assoc($qPeserta)['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Panitia</title>

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

            Dashboard Panitia

        </h3>

        <div class="row">

            <div class="col-md-4">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-easel display-4 text-primary"></i>

                        <h2><?= $totalSeminar; ?></h2>

                        <h5>Seminar Saya</h5>

                        <a href="seminar.php" class="btn btn-primary mt-2">

                            Kelola Seminar

                        </a>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-people display-4 text-success"></i>

                        <h2><?= $totalPeserta; ?></h2>

                        <h5>Total Peserta</h5>

                        <a href="peserta.php" class="btn btn-success mt-2">

                            Lihat Peserta

                        </a>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-person-circle display-4 text-warning"></i>

                        <h5 class="mt-3">

                            <?= $_SESSION['nama']; ?>

                        </h5>

                        <p>

                            Panitia Seminar

                        </p>

                        <a href="profil.php" class="btn btn-warning">

                            Profil

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <hr class="my-5">

        <h4>Menu Cepat</h4>

        <div class="row mt-3">

            <div class="col-md-3">

                <a href="seminar.php" class="btn btn-outline-primary w-100 p-3">

                    <i class="bi bi-plus-circle fs-2"></i>

                    <br>

                    Tambah Seminar

                </a>

            </div>

            <div class="col-md-3">

                <a href="seminar.php" class="btn btn-outline-success w-100 p-3">

                    <i class="bi bi-card-list fs-2"></i>

                    <br>

                    Seminar Saya

                </a>

            </div>

            <div class="col-md-3">

                <a href="peserta.php" class="btn btn-outline-warning w-100 p-3">

                    <i class="bi bi-people fs-2"></i>

                    <br>

                    Peserta

                </a>

            </div>

            <div class="col-md-3">

                <a href="../logout.php" class="btn btn-outline-danger w-100 p-3">

                    <i class="bi bi-box-arrow-right fs-2"></i>

                    <br>

                    Logout

                </a>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>