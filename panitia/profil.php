<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id = $_SESSION['id'];

$q = mysqli_query($koneksi, "
SELECT *
FROM panitia
WHERE id_panitia='$id'
");

$p = mysqli_fetch_assoc($q);

/* Statistik */

$totalSeminar = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM seminar
WHERE id_panitia='$id'
"))['total'];

$publish = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM seminar
WHERE id_panitia='$id'
AND status='Publish'
"))['total'];

$totalPeserta = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM pendaftaran
JOIN seminar
ON pendaftaran.id_seminar=seminar.id_seminar
WHERE seminar.id_panitia='$id'
"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM pendaftaran
JOIN seminar
ON pendaftaran.id_seminar=seminar.id_seminar
WHERE seminar.id_panitia='$id'
AND pendaftaran.status='Pending'
"))['total'];
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Profil Panitia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow">

                    <div class="card-body">

                        <div class="text-center mb-4">

                            <i class="bi bi-person-circle display-1 text-primary"></i>

                            <h3><?= $p['nama_panitia']; ?></h3>

                            <span class="badge bg-success">

                                <?= $p['status']; ?>

                            </span>

                        </div>

                        <table class="table">

                            <tr>

                                <th width="180">Universitas</th>

                                <td><?= $p['universitas']; ?></td>

                            </tr>

                            <tr>

                                <th>Email</th>

                                <td><?= $p['email']; ?></td>

                            </tr>

                            <tr>

                                <th>No HP</th>

                                <td><?= $p['telepon']; ?></td>

                            </tr>

                        </table>

                        <hr>

                        <div class="row text-center">

                            <div class="col">

                                <h3><?= $totalSeminar ?></h3>

                                Seminar

                            </div>

                            <div class="col">

                                <h3><?= $publish ?></h3>

                                Publish

                            </div>

                            <div class="col">

                                <h3><?= $totalPeserta ?></h3>

                                Peserta

                            </div>

                            <div class="col">

                                <h3><?= $pending ?></h3>

                                Pending

                            </div>

                        </div>

                        <hr>

                        <div class="text-end">

                            <a href="dashboard.php" class="btn btn-secondary">

                                Kembali

                            </a>

                            <a href="edit_profil.php" class="btn btn-primary">

                                <i class="bi bi-pencil"></i>

                                Edit Profil

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>