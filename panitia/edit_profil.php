<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id = $_SESSION['id'];

$q = mysqli_query($koneksi, "
SELECT *
FROM panitia
WHERE id_panitia='$id'
");

$p = mysqli_fetch_assoc($q);

if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);

    $universitas = mysqli_real_escape_string($koneksi, $_POST['universitas']);

    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);

    mysqli_query($koneksi, "
UPDATE panitia
SET
nama_panitia='$nama',
universitas='$universitas',
email='$email',
telepon='$telepon'
WHERE id_panitia='$id'
");

    $_SESSION['nama'] = $nama;

    echo "<script>
alert('Profil berhasil diperbarui');
window.location='profil.php';
</script>";
}
?>

<!doctype html>

<html>

<head>

    <meta charset="UTF-8">

    <title>Edit Profil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">

                        Edit Profil

                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <label>Nama Panitia</label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control mb-3"
                                value="<?= $p['nama_panitia']; ?>">

                            <label>Universitas</label>

                            <input
                                type="text"
                                name="universitas"
                                class="form-control mb-3"
                                value="<?= $p['universitas']; ?>">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control mb-3"
                                value="<?= $p['email']; ?>">

                            <label>No HP</label>

                            <input
                                type="text"
                                name="telepon"
                                class="form-control mb-4"
                                value="<?= $p['telepon']; ?>">

                            <button
                                class="btn btn-primary"
                                name="simpan">

                                Simpan

                            </button>

                            <a
                                href="profil.php"
                                class="btn btn-secondary">

                                Batal

                            </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>