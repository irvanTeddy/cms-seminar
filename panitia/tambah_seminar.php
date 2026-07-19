<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

/* ===========================
   AMBIL KATEGORI
=========================== */

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

/* ===========================
   SIMPAN DATA
=========================== */

if (isset($_POST['simpan'])) {

    $id_kategori = $_POST['id_kategori'];
    $judul       = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $pemateri    = mysqli_real_escape_string($koneksi, $_POST['pemateri']);
    $tanggal     = $_POST['tanggal'];
    $jam         = $_POST['jam'];
    $lokasi      = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $kuota       = $_POST['kuota'];
    $biaya       = $_POST['biaya'];

    $poster = "";

    if ($_FILES['poster']['name'] != "") {

        $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);

        $poster = time() . "." . $ext;

        move_uploaded_file(
            $_FILES['poster']['tmp_name'],
            "../uploads/poster/" . $poster
        );
    }

    mysqli_query($koneksi, "
    INSERT INTO seminar
    (
        id_panitia,
        id_kategori,
        judul,
        deskripsi,
        pemateri,
        tanggal,
        jam,
        lokasi,
        kuota,
        poster,
        biaya,
        status,
        created_at
    )
    VALUES
    (
        '$id_panitia',
        '$id_kategori',
        '$judul',
        '$deskripsi',
        '$pemateri',
        '$tanggal',
        '$jam',
        '$lokasi',
        '$kuota',
        '$poster',
        '$biaya',
        'Draft',
        NOW()
    )
    ");

    echo "<script>
    alert('Seminar berhasil ditambahkan dan menunggu Publish Admin');
    window.location='seminar.php';
    </script>";
}
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tambah Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow">

                    <div class="card-header bg-success text-white">

                        <h4>Tambah Seminar</h4>

                    </div>

                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">

                            <div class="mb-3">

                                <label>Kategori</label>

                                <select name="id_kategori" class="form-control" required>

                                    <option value="">-- Pilih Kategori --</option>

                                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                                        <option value="<?= $k['id_kategori']; ?>">

                                            <?= $k['nama_kategori']; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label>Judul Seminar</label>

                                <input type="text" name="judul" class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label>Pemateri</label>

                                <input type="text" name="pemateri" class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label>Deskripsi</label>

                                <textarea name="deskripsi" rows="5" class="form-control" required></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label>Tanggal</label>

                                    <input type="date" name="tanggal" class="form-control" required>

                                </div>

                                <div class="col-md-6">

                                    <label>Jam</label>

                                    <input type="time" name="jam" class="form-control" required>

                                </div>

                            </div>

                            <br>

                            <div class="mb-3">

                                <label>Lokasi</label>

                                <input type="text" name="lokasi" class="form-control" required>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label>Kuota</label>

                                    <input type="number" name="kuota" class="form-control" required>

                                </div>

                                <div class="col-md-6">

                                    <label>Biaya</label>

                                    <input type="number" name="biaya" class="form-control" value="0">

                                </div>

                            </div>

                            <br>

                            <div class="mb-3">

                                <label>Poster</label>

                                <input type="file" name="poster" class="form-control" required>

                            </div>

                            <button class="btn btn-success" name="simpan">

                                Simpan Seminar

                            </button>

                            <a href="seminar.php" class="btn btn-secondary">

                                Kembali

                            </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>