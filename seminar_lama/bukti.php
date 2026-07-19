<?php
require_once 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Transaksi tidak valid.");
}

$id_transaksi = htmlspecialchars(trim($_GET['id']));

$sql = "SELECT * FROM peserta WHERE id_transaksi = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $id_transaksi);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data pendaftaran tidak ada.");
}

$data = $result->fetch_assoc();
$stmt->close();
$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo $data['id_transaksi']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-secondary-subtle py-5">

    <div class="container">
        <div class="card border-0 shadow-lg p-5 mx-auto rounded-3 bg-white" id="invoiceArea" style="max-width: 850px;">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-secondary mb-0">INVOICE REGISTRASI</h2>
                    <span class="text-primary fw-bold small">Seminar Deforka Fest Web Development 2026</span>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <h3 class="fw-bold text-primary mb-0">Deforka fest</h3>
                </div>
            </div>
            
            <hr class="my-4 text-muted border-2">

            <div class="row mb-5 g-4">
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Ditujukan Kepada:</p>
                    <h5 class="fw-bold text-dark mb-1"><?php echo $data['nama']; ?></h5>
                    <p class="text-muted mb-0 small"><?php echo $data['universitas']; ?> (<?php echo $data['prodi']; ?>)</p>
                    <p class="text-muted mb-0 small">Email: <?php echo $data['email']; ?></p>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <p class="text-muted small mb-1">Rincian Kode:</p>
                    <p class="mb-0 small"><strong>ID Transaksi:</strong> <span class="text-primary fw-bold"><?php echo $data['id_transaksi']; ?></span></p>
                    <p class="mb-0 small"><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($data['tanggal_daftar'])); ?></p>
                    <p class="mb-0 small"><strong>Metode:</strong> <?php echo $data['metode_pembayaran']; ?></p>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi Item</th>
                            <th>Kategori</th>
                            <th class="text-end">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Tiket Akses Seminar Web Dev 2026<br>
                                <small class="text-muted text-uppercase font-xs">Kelas: <?php echo $data['kelas_seminar']; ?></small>
                            </td>
                            <td class="text-capitalize"><?php echo $data['jenis_peserta']; ?></td>
                            <td class="text-end fw-bold text-primary">
                                <?php echo ($data['kelas_seminar'] === 'vip') ? 'Rp 150.000' : 'Rp 50.000'; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row align-items-center g-3">
                <div class="col-sm-6">
                    <div id="qrcode" class="d-inline-block p-2 border bg-light rounded"></div>
                    <p class="text-muted font-xs mt-2 mb-0">Scan QR Code di atas untuk validasi tiket masuk panitia.</p>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <span class="text-muted d-block small mb-1">Status Verifikasi:</span>
                    <?php 
                    $status = $data['status_pembayaran'];
                    $badgeBg = 'bg-warning-subtle text-warning';
                    if ($status === 'Lunas') $badgeBg = 'bg-success-subtle text-success';
                    if ($status === 'Ditolak') $badgeBg = 'bg-danger-subtle text-danger';
                    ?>
                    <span class="badge <?php echo $badgeBg; ?> px-4 py-2 fs-6 fw-bold border rounded-2"><?php echo strtoupper($status); ?></span>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 print-hidden">
            <button onclick="window.print()" class="btn btn-primary px-4 py-2 fw-bold me-2 shadow-sm">Cetak Bukti</button>
            <button onclick="window.print()" class="btn btn-outline-dark px-4 py-2 fw-bold me-2 shadow-sm">Download PDF</button>
            <a href="index.php" class="btn btn-link text-decoration-none text-muted fw-semibold">Kembali ke Beranda</a>
        </div>
    </div>

    <script>
        // Render QR Code otomatis
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $data['id_transaksi']; ?>",
            width: 110,
            height: 110
        });
    </script>
</body>
</html>