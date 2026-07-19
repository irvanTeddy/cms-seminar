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

$id = (int)$_GET['id'];

$data = mysqli_query($koneksi, "
SELECT *
FROM seminar
WHERE id_seminar='$id'
AND id_panitia='$id_panitia'
");

if (mysqli_num_rows($data) == 0) {
    die("Seminar tidak ditemukan.");
}

$seminar = mysqli_fetch_assoc($data);

$kategori = mysqli_query($koneksi, "
SELECT *
FROM kategori
ORDER BY nama_kategori ASC
");

if (isset($_POST['update'])) {

    $id_kategori = $_POST['id_kategori'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $pemateri = mysqli_real_escape_string($koneksi, $_POST['pemateri']);
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $kuota = $_POST['kuota'];
    $biaya = $_POST['biaya'];

    $poster = $seminar['poster'];

    if ($_FILES['poster']['name'] != "") {

        if ($poster != "" && file_exists("../uploads/poster/" . $poster)) {
            unlink("../uploads/poster/" . $poster);
        }

        $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);

        $poster = time() . "." . $ext;

        move_uploaded_file(
            $_FILES['poster']['tmp_name'],
            "../uploads/poster/" . $poster
        );
    }

    mysqli_query($koneksi, "
    UPDATE seminar SET

    id_kategori='$id_kategori',
    judul='$judul',
    deskripsi='$deskripsi',
    pemateri='$pemateri',
    tanggal='$tanggal',
    jam='$jam',
    lokasi='$lokasi',
    kuota='$kuota',
    biaya='$biaya',
    poster='$poster'

    WHERE id_seminar='$id'
    ");

    echo "<script>

    alert('Seminar berhasil diperbarui');

    window.location='seminar.php';

    </script>";
}
?>

<!doctype html>

<html>

<head>

    <meta charset="UTF-8">

    <title>Edit Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow">

                    <div class="card-header bg-warning">

                        <h4>Edit Seminar</h4>

                    </div>

                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">

                            <label>Kategori</label>

                            <select name="id_kategori" class="form-control mb-3">

                                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                                    <option
                                        value="<?= $k['id_kategori']; ?>"
                                        <?= ($seminar['id_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>

                                        <?= $k['nama_kategori']; ?>

                                    </option>

                                <?php } ?>

                            </select>

                            <label>Judul</label>

                            <input
                                type="text"
                                name="judul"
                                value="<?= $seminar['judul']; ?>"
                                class="form-control mb-3">

                            <label>Pemateri</label>

                            <input
                                type="text"
                                name="pemateri"
                                value="<?= $seminar['pemateri']; ?>"
                                class="form-control mb-3">

                            <label>Deskripsi</label>

                            <textarea
                                name="deskripsi"
                                rows="5"
                                class="form-control mb-3"><?= $seminar['deskripsi']; ?></textarea>

                            <div class="row">

                                <div class="col-md-6">

                                    <label>Tanggal</label>

                                    <input
                                        type="date"
                                        name="tanggal"
                                        value="<?= $seminar['tanggal']; ?>"
                                        class="form-control">

                                </div>

                                <div class="col-md-6">

                                    <label>Jam</label>

                                    <input
                                        type="time"
                                        name="jam"
                                        value="<?= $seminar['jam']; ?>"
                                        class="form-control">

                                </div>

                            </div>

                            <br>

                            <label>Lokasi</label>

                            <input
                                type="text"
                                name="lokasi"
                                value="<?= $seminar['lokasi']; ?>"
                                class="form-control mb-3">

                            <div class="row">

                                <div class="col-md-6">

                                    <label>Kuota</label>

                                    <input
                                        type="number"
                                        name="kuota"
                                        value="<?= $seminar['kuota']; ?>"
                                        class="form-control">

                                </div>

                                <div class="col-md-6">

                                    <label>Biaya</label>

                                    <input
                                        type="number"
                                        name="biaya"
                                        value="<?= $seminar['biaya']; ?>"
                                        class="form-control">

                                </div>

                            </div>

                            <br>

                            <?php if ($seminar['poster'] != "") { ?>

                                <img
                                    src="../uploads/poster/<?= $seminar['poster']; ?>"
                                    width="180"
                                    class="mb-3">

                            <?php } ?>

                            <label>Ganti Poster</label>

                            <input
                                type="file"
                                name="poster"
                                class="form-control mb-4">

                            <button
                                class="btn btn-warning"
                                name="update">

                                Update Seminar

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