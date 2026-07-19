<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">Deforka fest</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Beranda</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow p-4 rounded-3">
                    <h2 class="fw-bold text-center text-dark mb-2">Formulir Pendaftaran</h2>
                    <p class="text-muted text-center small mb-5">Lengkapi data di bawah ini untuk proses verifikasi e-sertifikat.</p>
                    
                    <form action="proses_daftar.php" method="POST" enctype="multipart/form-data" id="formSeminar">
                        
                        <div class="mb-4 p-4 bg-light rounded-3 border">
                            <h5 class="fw-bold mb-3 text-primary">1. Data Diri Peserta</h5>
                            
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold text-secondary">Nama Lengkap *</label>
                                <input type="text" id="nama" name="nama" class="form-control" required placeholder="Masukkan nama tanpa gelar">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="nim" class="form-label fw-semibold text-secondary">NIM / NIK *</label>
                                    <input type="text" id="nim" name="nim" class="form-control" required placeholder="Contoh: 220010123">
                                </div>
                                <div class="col-md-6">
                                    <label for="jenis_kelamin" class="form-label fw-semibold text-secondary">Jenis Kelamin *</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="universitas" class="form-label fw-semibold text-secondary">Universitas / Instansi *</label>
                                    <input type="text" id="universitas" name="universitas" class="form-control" required placeholder="Nama Kampus/Instansi">
                                </div>
                                <div class="col-md-6">
                                    <label for="prodi" class="form-label fw-semibold text-secondary">Program Studi / Jabatan *</label>
                                    <input type="text" id="prodi" name="prodi" class="form-control" required placeholder="Contoh: Informatika">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="whatsapp" class="form-label fw-semibold text-secondary">Nomor WhatsApp *</label>
                                    <input type="tel" id="whatsapp" name="whatsapp" class="form-control" required placeholder="Contoh: 0812345678">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-secondary">Alamat Email *</label>
                                    <input type="email" id="email" name="email" class="form-control" required placeholder="Contoh: user@email.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold text-secondary">Alamat Lengkap *</label>
                                <textarea id="alamat" name="alamat" rows="3" class="form-control" required placeholder="Alamat rumah lengkap"></textarea>
                            </div>
                        </div>

                        <div class="mb-4 p-4 bg-light rounded-3 border">
                            <h5 class="fw-bold mb-3 text-primary">2. Pilihan Kelas & Metode</h5>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="jenis_peserta" class="form-label fw-semibold text-secondary">Jenis Peserta *</label>
                                    <select id="jenis_peserta" name="jenis_peserta" class="form-select" required>
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="umum">Umum</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="kelas_seminar" class="form-label fw-semibold text-secondary">Kelas Seminar *</label>
                                    <select id="kelas_seminar" name="kelas_seminar" class="form-select" required>
                                        <option value="regular">Regular (Rp 50.000)</option>
                                        <option value="vip">VIP (Rp 150.000)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label fw-semibold text-secondary">Metode Pembayaran *</label>
                                <select id="metode_pembayaran" name="metode_pembayaran" class="form-select" required>
                                    <option value="Transfer Bank">Transfer Bank (Mandiri: 123-456-789 a.n novrian)</option>
                                    <option value="DANA">DANA (0812-3456-7890)</option>
                                    <option value="OVO">OVO (0812-3456-7890)</option>
                                    <option value="GoPay">GoPay (0812-3456-7890)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4 p-4 bg-light rounded-3 border">
                            <h5 class="fw-bold mb-3 text-primary">3. Upload Bukti Transfer</h5>
                            <div class="mb-2">
                                <label for="bukti_transfer" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed">
                                    <span class="d-block fw-bold">Pilih File Bukti Pembayaran</span>
                                    <small class="text-muted d-block font-xs">Format: JPG, JPEG, PNG (Maksimal 2MB)</small>
                                </label>
                                <input type="file" id="bukti_transfer" name="bukti_transfer" class="form-control d-none" accept=".jpg, .jpeg, .png" required>
                                <div id="file-preview-name" class="text-center mt-3 small fw-bold text-success"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3 shadow">Daftar Seminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>