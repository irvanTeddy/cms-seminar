<?php
// 1. Hubungkan ke database
require_once 'koneksi.php';

// 2. Ambil data konten seminar dari database
$query = $koneksi->query("SELECT * FROM seminar_info WHERE id = 1");
$info = $query->fetch_assoc();
$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $info['judul']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">Deforka Fest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-shadow"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-semibold">
                    <li class="nav-item"><a class="nav-link text-primary" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar.php">Pendaftaran</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn btn-outline-primary btn-sm px-3" href="admin/login.php">Admin Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-primary-subtle text-primary mb-3 px-3 py-2 rounded-pill fw-bold"><?php echo $info['badge_text']; ?></span>
                
                <h1 class="display-5 fw-bold text-dark lh-sm mb-3"><?php echo $info['judul']; ?></h1>
                
                <p class="lead text-primary fw-semibold mb-4">Tema: "<?php echo $info['tema']; ?>"</p>
                
                <div class="d-flex gap-3 mb-4 flex-wrap">
                    <div class="p-3 bg-white border-start border-primary border-4 rounded shadow-sm">
                        <small class="text-muted d-block">Tanggal pelaksanaan</small>
                        <strong class="text-dark"><?php echo $info['tanggal']; ?></strong>
                    </div>
                    <div class="p-3 bg-white border-start border-primary border-4 rounded shadow-sm">
                        <small class="text-muted d-block">Tempat</small>
                        <strong class="text-dark"><?php echo $info['lokasi']; ?></strong>
                    </div>
                </div>

                <p class="text-muted mb-4"><?php echo $info['deskripsi']; ?></p>
                <a href="daftar.php" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow">Daftar Sekarang</a>
            </div>
            
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg p-4 rounded-4">
                    <div class="card-body">
                        <h3 class="fw-bold text-dark mb-2">Pendaftaran Dibuka!</h3>
                        <p class="text-muted small mb-4">Kuota Terbatas untuk <?php echo $info['kuota_maks']; ?> Peserta Pertama</p>
                        <hr class="text-muted">
                        <ul class="list-unstyled ready-list">
                            <li class="mb-3 text-dark fw-medium"><span class="text-success me-2">&checkmark;</span> E-Sertifikat Resmi (Mendukung SKPI)</li>
                            <li class="mb-3 text-dark fw-medium"><span class="text-success me-2">&checkmark;</span> Full Source Code Project Website</li>
                            <li class="mb-3 text-dark fw-medium"><span class="text-success me-2">&checkmark;</span> Relasi Komunitas Developer</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white py-4 mt-auto border-top">
        <div class="container text-center text-muted">
            <small>&copy;  Sistem Pendaftaran Seminar. Deforka Fest.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>