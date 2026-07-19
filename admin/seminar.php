<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

/* ==========================
   PUBLISH SEMINAR
========================== */

if (isset($_GET['publish'])) {

    $id = (int)$_GET['publish'];

    mysqli_query($koneksi, "
    UPDATE seminar
    SET status='Publish'
    WHERE id_seminar='$id'
    ");

    header("Location: seminar.php");
    exit;
}

/* ==========================
   JADIKAN DRAFT
========================== */

if (isset($_GET['draft'])) {

    $id = (int)$_GET['draft'];

    mysqli_query($koneksi, "
    UPDATE seminar
    SET status='Draft'
    WHERE id_seminar='$id'
    ");

    header("Location: seminar.php");
    exit;
}

/* ==========================
   HAPUS
========================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "
    DELETE FROM seminar
    WHERE id_seminar='$id'
    ");

    header("Location: seminar.php");
    exit;
}

/* ==========================
   AMBIL DATA
========================== */

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

ORDER BY seminar.id_seminar DESC
");
?>

<!doctype html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Data Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand fw-bold">

                CMS Seminar Informatika

            </a>

            <div class="text-white">

                <?= $_SESSION['nama']; ?>

                |

                <a href="../logout.php" class="text-white text-decoration-none">

                    Logout

                </a>

            </div>

        </div>

    </nav>

    <div class="container mt-4">

        <h3>Semua Seminar</h3>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>

                            <th>Poster</th>

                            <th>Judul</th>

                            <th>Panitia</th>

                            <th>Kategori</th>

                            <th>Status</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query)) {

                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td width="120">

                                    <?php if ($row['poster'] != "") { ?>

                                        <img src="../uploads/poster/<?= $row['poster']; ?>" width="90">

                                    <?php } ?>

                                </td>

                                <td>

                                    <b><?= $row['judul']; ?></b>

                                    <br>

                                    <?= $row['tanggal']; ?>

                                </td>

                                <td>

                                    <?= $row['nama_panitia']; ?>

                                </td>

                                <td>

                                    <?= $row['nama_kategori']; ?>

                                </td>

                                <td>

                                    <?php

                                    if ($row['status'] == "Publish") {

                                        echo "<span class='badge bg-success'>Publish</span>";
                                    } else {

                                        echo "<span class='badge bg-warning text-dark'>Draft</span>";
                                    }

                                    ?>

                                </td>

                                <td>

                                    <?php

                                    if ($row['status'] == "Draft") {

                                    ?>

                                        <a
                                            href="?publish=<?= $row['id_seminar']; ?>"
                                            class="btn btn-success btn-sm">

                                            Publish

                                        </a>

                                    <?php

                                    } else {

                                    ?>

                                        <a
                                            href="?draft=<?= $row['id_seminar']; ?>"
                                            class="btn btn-warning btn-sm">

                                            Draft

                                        </a>

                                    <?php } ?>

                                    <a
                                        href="?hapus=<?= $row['id_seminar']; ?>"
                                        onclick="return confirm('Hapus seminar?')"
                                        class="btn btn-danger btn-sm">

                                        Hapus

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>
                <a href="dashboard.php" class="btn btn-secondary">

                    ← Kembali

                </a>
            </div>

        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>