<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id'];

if (!isset($_GET['id'])) {
    header("Location: seminar.php");
    exit;
}

$id_seminar = (int)$_GET['id'];

/* ===========================
   AMBIL DATA SEMINAR
=========================== */

$q = mysqli_query($koneksi, "
SELECT seminar.*, panitia.nama_panitia
FROM seminar
JOIN panitia ON seminar.id_panitia=panitia.id_panitia
WHERE id_seminar='$id_seminar'
");

$seminar = mysqli_fetch_assoc($q);

if (!$seminar) {
    die("Seminar tidak ditemukan.");
}

/* ===========================
   CEK SUDAH DAFTAR BELUM
=========================== */

$cek = mysqli_query($koneksi, "
SELECT *
FROM pendaftaran
WHERE id_user='$id_user'
AND id_seminar='$id_seminar'
");

if (mysqli_num_rows($cek) > 0) {

    echo "<script>
    alert('Anda sudah mendaftar seminar ini!');
    window.location='seminar.php';
    </script>";
    exit;
}

/* ===========================
   SIMPAN
=========================== */

if (isset($_POST['daftar'])) {

    $bukti = "";

    if ($_FILES['bukti']['name'] != "") {

        $bukti = time() . "_" . $_FILES['bukti']['name'];

        move_uploaded_file(
            $_FILES['bukti']['tmp_name'],
            "../uploads/bukti/" . $bukti
        );
    }

    mysqli_query($koneksi, "
    INSERT INTO pendaftaran
    (
        id_user,
        id_seminar,
        bukti_pembayaran,
        status
    )
    VALUES
    (
        '$id_user',
        '$id_seminar',
        '$bukti',
        'Pending'
    )
    ");

    echo "<script>
    alert('Pendaftaran berhasil.');
    window.location='seminar_saya.php';
    </script>";
}
?>

<!doctype html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Daftar Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">

                        <h4>Form Pendaftaran Seminar</h4>

                    </div>

                    <div class="card-body">

                        <p>

                            <b>Judul Seminar</b>

                            <br>

                            <?= $seminar['judul']; ?>

                        </p>

                        <p>

                            <b>Panitia</b>

                            <br>

                            <?= $seminar['nama_panitia']; ?>

                        </p>

                        <p>

                            <b>Tanggal</b>

                            <br>

                            <?= date('d-m-Y', strtotime($seminar['tanggal'])); ?>

                        </p>

                        <p>

                            <b>Biaya</b>

                            <br>

                            Rp <?= number_format($seminar['biaya']); ?>

                        </p>
                        <div class="alert alert-warning mt-3">

                            <h6 class="fw-bold">
                                <i class="bi bi-bank"></i>
                                Informasi Seminar & Pembayaran
                            </h6>

                            <?= nl2br(htmlspecialchars($seminar['deskripsi'])) ?>

                        </div>

                        <form method="POST" enctype="multipart/form-data">

                            <div class="mb-3">

                                <label>Bukti Pembayaran (Transfer sesuai rekening di poster)</label>

                                <input
                                    type="file"
                                    name="bukti"
                                    class="form-control"
                                    required>

                            </div>

                            <button
                                class="btn btn-success"
                                name="daftar">

                                Daftar Seminar

                            </button>

                            <a
                                href="seminar.php"
                                class="btn btn-secondary">

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