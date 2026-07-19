<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input dasar
    $nama              = htmlspecialchars(strip_tags(trim($_POST['nama'])));
    $nim               = htmlspecialchars(strip_tags(trim($_POST['nim'])));
    $universitas       = htmlspecialchars(strip_tags(trim($_POST['universitas'])));
    $prodi             = htmlspecialchars(strip_tags(trim($_POST['prodi'])));
    $jenis_kelamin     = htmlspecialchars(strip_tags(trim($_POST['jenis_kelamin'])));
    $whatsapp          = htmlspecialchars(strip_tags(trim($_POST['whatsapp'])));
    $email             = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $alamat            = htmlspecialchars(strip_tags(trim($_POST['alamat'])));
    $jenis_peserta     = htmlspecialchars(strip_tags(trim($_POST['jenis_peserta'])));
    $kelas_seminar     = htmlspecialchars(strip_tags(trim($_POST['kelas_seminar'])));
    $metode_pembayaran = htmlspecialchars(strip_tags(trim($_POST['metode_pembayaran'])));
    
    if (empty($nama) || empty($nim) || empty($email) || empty($whatsapp)) {
        die("Error: Seluruh form wajib diisi.");
    }

    // Pembuatan ID Transaksi Otomatis (TRX + Tanggal Hari Ini + Angka Random 4 Digit)
    $tanggal_sekarang = date('Ymd');
    $angka_random     = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $id_transaksi     = "TRX" . $tanggal_sekarang . $angka_random;

    // Proses File Upload Bukti Transfer
    $file        = $_FILES['bukti_transfer'];
    $file_name   = $file['name'];
    $file_tmp    = $file['tmp_name'];
    $file_size   = $file['size'];
    $file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $max_size    = 2 * 1024 * 1024; // 2 MB

    if (!in_array($file_ext, $allowed_ext)) {
        die("Error: Ekstensi file salah. Harus JPG, JPEG, atau PNG.");
    }

    if ($file_size > $max_size) {
        die("Error: Ukuran berkas maksimal adalah 2 MB.");
    }

    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Modifikasi nama file bukti transfer agar unik
    $new_file_name = $id_transaksi . '.' . $file_ext;
    $destination   = $upload_dir . $new_file_name;

    if (!move_uploaded_file($file_tmp, $destination)) {
        die("Error: Gagal mengunggah gambar bukti transfer.");
    }

    // Insert Data Menggunakan Prepared Statement (Mencegah SQL Injection)
    $status_pembayaran = "Menunggu Verifikasi";
    $sql = "INSERT INTO peserta (id_transaksi, nama, nim, universitas, prodi, jenis_kelamin, whatsapp, email, alamat, jenis_peserta, kelas_seminar, metode_pembayaran, bukti_transfer, status_pembayaran) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssssssssssssss", 
            $id_transaksi, $nama, $nim, $universitas, $prodi, 
            $jenis_kelamin, $whatsapp, $email, $alamat, 
            $jenis_peserta, $kelas_seminar, $metode_pembayaran, 
            $new_file_name, $status_pembayaran
        );
        
        if ($stmt->execute()) {
            header("Location: bukti.php?id=" . $id_transaksi);
            exit();
        } else {
            echo "Gagal menyimpan ke database: " . $stmt->error;
        }
        $stmt->close();
    }
    $koneksi->close();
}
?>