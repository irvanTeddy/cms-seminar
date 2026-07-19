<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

if (!isset($_GET['id'])) {
    header("Location: seminar.php");
    exit;
}

$id_seminar = (int)$_GET['id'];

/* ==========================
   CEK SEMINAR MILIK PANITIA
========================== */

$cek = mysqli_query($koneksi, "
SELECT *
FROM seminar
WHERE id_seminar='$id_seminar'
AND id_panitia='$id_panitia'
");

if (mysqli_num_rows($cek) == 0) {
    die("Seminar tidak ditemukan atau bukan milik Anda.");
}

$seminar = mysqli_fetch_assoc($cek);

/* ==========================
   VERIFIKASI
========================== */

if (isset($_GET['verifikasi'])) {

    $id = (int)$_GET['verifikasi'];

    mysqli_query($koneksi, "
    UPDATE pendaftaran
    SET status='Diverifikasi'
    WHERE id_daftar='$id'
    ");

    header("Location: peserta.php?id=" . $id_seminar);
    exit;
}

/* ==========================
   TOLAK
========================== */

if (isset($_GET['tolak'])) {

    $id = (int)$_GET['tolak'];

    mysqli_query($koneksi, "
    UPDATE pendaftaran
    SET status='Ditolak'
    WHERE id_daftar='$id'
    ");

    header("Location: peserta.php?id=" . $id_seminar);
    exit;
}

/* ==========================
   HAPUS PESERTA
========================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "
    DELETE FROM pendaftaran
    WHERE id_daftar='$id'
    ");

    header("Location: peserta.php?id=" . $id_seminar);
    exit;
}

/* ==========================
   DATA PESERTA
========================== */

$data = mysqli_query($koneksi, "
SELECT
pendaftaran.*,
users.nama,
users.nim,
users.universitas,
users.email
FROM pendaftaran
JOIN users
ON pendaftaran.id_user = users.id_user
WHERE pendaftaran.id_seminar='$id_seminar'
ORDER BY pendaftaran.id_daftar DESC
");
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Peserta Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="d-flex justify-content-between mb-3">

            <h3>Peserta Seminar</h3>

            <a href="seminar.php" class="btn btn-secondary">
                Kembali
            </a>

        </div>

        <div class="alert alert-info">

            <b><?= htmlspecialchars($seminar['judul']); ?></b>

        </div>

        <div class="card shadow">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>No</th>
                                <th>Nama Peserta</th>
                                <th>Asal Kampus</th>
                                <th>NIM</th>
                                <th>Email</th>
                                <th>Bukti Pembayaran</th>
                                <th>Status</th>
                                <th width="200">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if (mysqli_num_rows($data) > 0) {

                                $no = 1;

                                while ($row = mysqli_fetch_assoc($data)) {

                            ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>

                                            <strong><?= htmlspecialchars($row['nama']); ?></strong>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['universitas']); ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['nim']); ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['email']); ?>

                                        </td>

                                        <td>

                                            <?php if (!empty($row['bukti_pembayaran'])) { ?>

                                                <a
                                                    href="../uploads/bukti/<?= $row['bukti_pembayaran']; ?>"
                                                    target="_blank"
                                                    class="btn btn-primary btn-sm">

                                                    Lihat Bukti

                                                </a>

                                            <?php } else { ?>

                                                <span class="text-danger">

                                                    Belum Upload

                                                </span>

                                            <?php } ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($row['status'] == "Pending") {

                                                echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                            } elseif ($row['status'] == "Diverifikasi") {

                                                echo "<span class='badge bg-success'>Diverifikasi</span>";
                                            } else {

                                                echo "<span class='badge bg-danger'>Ditolak</span>";
                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <?php if ($row['status'] == "Pending") { ?>

                                                <a
                                                    href="?id=<?= $id_seminar ?>&verifikasi=<?= $row['id_daftar']; ?>"
                                                    class="btn btn-success btn-sm mb-1">

                                                    <i class="bi bi-check-circle"></i>

                                                    Verifikasi

                                                </a>

                                                <a
                                                    href="?id=<?= $id_seminar ?>&tolak=<?= $row['id_daftar']; ?>"
                                                    class="btn btn-warning btn-sm mb-1">

                                                    <i class="bi bi-x-circle"></i>

                                                    Tolak

                                                </a>

                                            <?php } ?>

                                            <a
                                                href="?id=<?= $id_seminar ?>&hapus=<?= $row['id_daftar']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus peserta ini?')">

                                                <i class="bi bi-trash"></i>

                                                Hapus

                                            </a>

                                        </td>

                                    </tr>

                                <?php

                                }
                            } else {

                                ?>

                                <tr>

                                    <td colspan="8" class="text-center">

                                        Belum ada peserta yang mendaftar.

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

</body>

</html>