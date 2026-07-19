<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id'];

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id_daftar = (int)$_GET['id'];

$query = mysqli_query($koneksi, "
SELECT
    pendaftaran.id_daftar,
    pendaftaran.status AS status_pendaftaran,
    pendaftaran.tanggal_daftar,

    users.nama,
    users.email,

    seminar.judul,
    seminar.pemateri,
    seminar.tanggal,
    seminar.jam,
    seminar.lokasi,
    seminar.biaya

FROM pendaftaran

JOIN users
ON pendaftaran.id_user = users.id_user

JOIN seminar
ON pendaftaran.id_seminar = seminar.id_seminar

WHERE
pendaftaran.id_daftar='$id_daftar'
AND
pendaftaran.id_user='$id_user'
LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    die("Data tidak ditemukan.");
}

$data = mysqli_fetch_assoc($query);

if ($data['status_pendaftaran'] != "Diverifikasi") {
    die("Pendaftaran belum diverifikasi.");
}
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Bukti Pendaftaran Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {

            background: #f5f5f5;
            padding: 40px;

        }

        .kartu {

            max-width: 850px;
            margin: auto;
            background: white;
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 35px;

        }

        .logo {

            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;

        }

        table td {

            padding: 8px;

        }
    </style>

</head>

<body>

    <div class="kartu">

        <div class="text-center">

            <div class="logo">

                CMS SEMINAR

            </div>

            <h3>

                BUKTI PENDAFTARAN SEMINAR

            </h3>

            <p>

                Dokumen ini merupakan bukti resmi bahwa peserta telah terverifikasi.

            </p>

            <hr>

        </div>

        <table class="table table-bordered">

            <tr>

                <th width="220">Nomor Pendaftaran</th>

                <td>

                    SEM-<?= str_pad($data['id_daftar'], 5, "0", STR_PAD_LEFT); ?>

                </td>

            </tr>

            <tr>

                <th>Nama Peserta</th>

                <td><?= $data['nama']; ?></td>

            </tr>

            <tr>

                <th>Email</th>

                <td><?= $data['email']; ?></td>

            </tr>

            <tr>

                <th>Nama Seminar</th>

                <td><?= $data['judul']; ?></td>

            </tr>

            <tr>

                <th>Pemateri</th>

                <td><?= $data['pemateri']; ?></td>

            </tr>

            <tr>

                <th>Tanggal</th>

                <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>

            </tr>

            <tr>

                <th>Jam</th>

                <td><?= date('H:i', strtotime($data['jam'])); ?> WIB</td>

            </tr>

            <tr>

                <th>Lokasi</th>

                <td><?= $data['lokasi']; ?></td>

            </tr>

            <tr>

                <th>Biaya</th>

                <td>

                    Rp <?= number_format($data['biaya'], 0, ',', '.'); ?>

                </td>

            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <span class="badge bg-success">

                        DIVERIFIKASI

                    </span>

                </td>

            </tr>

            <tr>

                <th>Tanggal Daftar</th>

                <td>

                    <?= date('d-m-Y H:i', strtotime($data['tanggal_daftar'])); ?>

                </td>

            </tr>

        </table>

        <div class="alert alert-success mt-4">

            <b>Selamat!</b><br>

            Anda telah resmi menjadi peserta seminar ini.
            Silakan tunjukkan bukti ini kepada panitia saat registrasi ulang.

        </div>

        <div class="text-center mt-4">

            <button onclick="window.print()" class="btn btn-primary">

                🖨 Cetak / Simpan PDF

            </button>

            <a href="seminar_saya.php" class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </div>

</body>

</html>