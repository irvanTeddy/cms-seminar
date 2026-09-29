<?php
require 'config/koneksi.php';

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

$totalSeminar = mysqli_num_rows($query);
?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CMS Seminar Informatika</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
        }

        .navbar {
            box-shadow: 0 2px 12px rgba(0, 0, 0, .12);

        }



        .hero {

            background: linear-gradient(135deg, #0d6efd, #4f8ef7);
            color: white;
            padding: 90px 0;

        }

        .hero h1 {

            font-weight: 700;

        }

        .hero p {

            font-size: 18px;

        }

        .hero img {

            max-width: 280px;

        }

        .stat-card {

            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);

        }

        .card {

            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: .3s;

        }

        .card:hover {

            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);

        }

        .card img {

            transition: .3s;

        }

        .card:hover img {

            transform: scale(1.05);

        }

        .badge-kategori {

            background: #0d6efd;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;

        }

        .info {

            margin-bottom: 10px;

        }

        .info i {

            color: #0d6efd;
            width: 22px;

        }

        .btn {

            border-radius: 10px;

        }

        footer {

            margin-top: 60px;

        }

        footer {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7) !important;
            /* Warna biru navbar dengan gradasi halus */
            color: rgba(255, 255, 255, 0.85) !important;
            margin-top: 80px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        footer h5 {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        footer p,
        footer small {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Kotak Ikon Kontak Kaca (Glassmorphism) */
        footer .footer-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            margin-right: 10px;
        }

        footer hr {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }
    </style>

</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">

        <div class="container">

            <a class="navbar-brand fw-bold">

                <i class="bi bi-mortarboard-fill"></i>

                Seminar IT

            </a>

            <div>

                <a href="login.php" class="btn btn-light me-2">

                    <i class="bi bi-box-arrow-in-right"></i>

                    Login

                </a>

                <a href="register.php" class="btn btn-warning">

                    <i class="bi bi-person-plus-fill"></i>

                    Daftar

                </a>

            </div>

        </div>

    </nav>

    <!-- HERO -->

    <section class="hero">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h1 class="display-4">

                        Temukan Seminar Informatika Terbaik

                    </h1>

                    <p class="mt-3">

                        Platform seminar yang dikelola oleh berbagai panitia mahasiswa Informatika di seluruh kampus Yogyakarta.
                        Temukan seminar, workshop, dan pelatihan yang sesuai dengan minatmu.

                    </p>

                    <a href="#seminar" class="btn btn-warning btn-lg mt-3">

                        <i class="bi bi-calendar-event"></i>

                        Lihat Seminar

                    </a>

                </div>

                <div class="col-lg-5 text-center">

                    <i class="bi-code-slash display-1"
                        style="font-size:180px;opacity:.25;"></i>

                </div>

            </div>

        </div>

    </section>

    <!-- Statistik -->

    <div class="container mt-5">

        <div class="row">

            <div class="col-md-4 mb-3">

                <div class="card stat-card p-4 text-center">

                    <h2 class="text-primary">

                        <i class="bi bi-calendar2-event"></i>

                    </h2>

                    <h3><?= $totalSeminar ?></h3>

                    <p class="text-muted">

                        Seminar Aktif

                    </p>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="card stat-card p-4 text-center">

                    <h2 class="text-success">

                        <i class="bi bi-people-fill"></i>

                    </h2>

                    <h3>Multi Panitia</h3>

                    <p class="text-muted">

                        Diselenggarakan oleh berbagai panitia

                    </p>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="card stat-card p-4 text-center">

                    <h2 class="text-danger">

                        <i class="bi bi-award-fill"></i>

                    </h2>

                    <h3>Informatika</h3>

                    <p class="text-muted">

                        Seminar & Workshop

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- SEMINAR -->
    <!-- SEMINAR -->

    <div class="container my-5" id="seminar">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold">

                <i class="bi bi-calendar-event text-primary"></i>

                Seminar Tersedia

            </h2>

            <span class="badge bg-primary p-2">

                <?= $totalSeminar ?> Seminar

            </span>

        </div>

        <div class="row">

            <?php

            if (mysqli_num_rows($query) == 0) {

            ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        <i class="bi bi-exclamation-circle"></i>

                        Belum ada seminar yang dipublikasikan.

                    </div>

                </div>

            <?php

            }

            mysqli_data_seek($query, 0);

            while ($row = mysqli_fetch_assoc($query)) {

            ?>

                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="card h-100 shadow-sm">

                        <?php

                        if ($row['poster'] != "") {

                        ?>

                            <img

                                src="uploads/poster/<?= $row['poster']; ?>"

                                class="card-img-top"

                                style="height:220px;object-fit:cover;">

                        <?php

                        } else {

                        ?>

                            <img

                                src="https://via.placeholder.com/600x350?text=Poster+Seminar"

                                class="card-img-top"

                                style="height:220px;object-fit:cover;">

                        <?php } ?>

                        <div class="card-body">

                            <span class="badge badge-kategori mb-3">

                                <?= $row['nama_kategori']; ?>

                            </span>

                            <h5 class="fw-bold">

                                <?= $row['judul']; ?>

                            </h5>

                            <p class="text-muted mb-3">

                                <?= $row['nama_panitia']; ?>

                            </p>

                            <div class="info">

                                <i class="bi bi-person-video3"></i>

                                <?= $row['pemateri']; ?>

                            </div>

                            <div class="info">

                                <i class="bi bi-calendar3"></i>

                                <?= date('d F Y', strtotime($row['tanggal'])); ?>

                            </div>

                            <div class="info">

                                <i class="bi bi-clock"></i>

                                <?= date('H:i', strtotime($row['jam'])); ?> WIB

                            </div>

                            <div class="info">

                                <i class="bi bi-geo-alt-fill"></i>

                                <?= $row['lokasi']; ?>

                            </div>

                            <div class="info">

                                <i class="bi bi-people-fill"></i>

                                Kuota :

                                <?= $row['kuota']; ?> Peserta

                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted">

                                        Biaya

                                    </small>

                                    <h5 class="text-primary mb-0">

                                        Rp <?= number_format($row['biaya'], 0, ',', '.'); ?>

                                    </h5>

                                </div>

                                <a href="login.php"

                                    class="btn btn-primary">

                                    Daftar

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <!-- FOOTER BIRU ELEGAN -->
    <footer class="py-5">

        <div class="container">

            <div class="row g-4 justify-content-between">

                <!-- Brand & Deskripsi -->
                <div class="col-lg-5 col-md-6">

                    <a class="text-decoration-none text-white fw-bold fs-5 d-flex align-items-center gap-2 mb-3" href="#">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>Seminar<span class="text-warning">IT</span></span>
                    </a>

                    <p class="mb-0 text-white-50">
                        Platform pendaftaran seminar terpadu yang memudahkan mahasiswa menemukan berbagai event Informatika, workshop, dan pelatihan dari kampus-kampus di Yogyakarta.
                    </p>

                </div>

                <!-- Kontak -->
                <div class="col-lg-4 col-md-6 text-md-end">

                    <h5 class="mb-3">Hubungi Kami</h5>

                    <p class="mb-2 d-flex align-items-center justify-content-md-end">
                        <span class="footer-icon"><i class="bi bi-envelope-fill"></i></span>
                        <span class="text-white">admin@seminar.com</span>
                    </p>

                    <p class="mb-0 d-flex align-items-center justify-content-md-end">
                        <span class="footer-icon"><i class="bi bi-telephone-fill"></i></span>
                        <span class="text-white">+62 812-3456-7890</span>
                    </p>

                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center small text-white-50">
                <p class="mb-2 mb-sm-0">© 2026 Irvan. All rights reserved.</p>
                <span>Dibuat untuk Komunitas IT Jogja</span>
            </div>

        </div>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>