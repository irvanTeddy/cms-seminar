<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

/* ==========================
   APPROVE / AKTIFKAN PANITIA
========================== */

if (isset($_GET['approve'])) {

    $id = (int)$_GET['approve'];

    mysqli_query($koneksi, "
        UPDATE panitia
        SET status='Aktif'
        WHERE id_panitia='$id'
    ");

    header("Location: panitia.php");
    exit;
}

/* ==========================
   NONAKTIFKAN PANITIA
========================== */

if (isset($_GET['nonaktif'])) {

    $id = (int)$_GET['nonaktif'];

    mysqli_query($koneksi, "
        UPDATE panitia
        SET status='Nonaktif'
        WHERE id_panitia='$id'
    ");

    header("Location: panitia.php");
    exit;
}

/* ==========================
   HAPUS PANITIA
========================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM panitia
        WHERE id_panitia='$id'
    ");

    header("Location: panitia.php");
    exit;
}

/* ==========================
   DATA PANITIA
========================== */

$data = mysqli_query($koneksi, "
SELECT *
FROM panitia
ORDER BY id_panitia DESC
");
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Panitia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand fw-bold">
                Super Admin
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

        <h3 class="mb-4">

            Data Panitia

        </h3>


        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>
                            <th>Nama Panitia</th>
                            <th>Universitas</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th width="260">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($p = mysqli_fetch_assoc($data)) {

                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td><?= htmlspecialchars($p['nama_panitia']); ?></td>

                                <td><?= htmlspecialchars($p['universitas']); ?></td>

                                <td><?= htmlspecialchars($p['email']); ?></td>

                                <td><?= htmlspecialchars($p['telepon']); ?></td>

                                <td>

                                    <?php

                                    switch ($p['status']) {

                                        case "Pending":
                                            echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                            break;

                                        case "Aktif":
                                            echo "<span class='badge bg-success'>Aktif</span>";
                                            break;

                                        case "Nonaktif":
                                            echo "<span class='badge bg-danger'>Nonaktif</span>";
                                            break;

                                        default:
                                            echo "<span class='badge bg-secondary'>" . $p['status'] . "</span>";
                                    }

                                    ?>

                                </td>

                                <td>

                                    <?php if ($p['status'] == "Pending") { ?>

                                        <a
                                            href="?approve=<?= $p['id_panitia']; ?>"
                                            class="btn btn-success btn-sm">

                                            <i class="bi bi-check-circle"></i>

                                            Approve

                                        </a>

                                    <?php } ?>

                                    <?php if ($p['status'] == "Aktif") { ?>

                                        <a
                                            href="?nonaktif=<?= $p['id_panitia']; ?>"
                                            class="btn btn-warning btn-sm">

                                            <i class="bi bi-slash-circle"></i>

                                            Nonaktif

                                        </a>

                                    <?php } ?>

                                    <?php if ($p['status'] == "Nonaktif") { ?>

                                        <a
                                            href="?approve=<?= $p['id_panitia']; ?>"
                                            class="btn btn-primary btn-sm">

                                            <i class="bi bi-arrow-repeat"></i>

                                            Aktifkan

                                        </a>

                                    <?php } ?>

                                    <a
                                        href="?hapus=<?= $p['id_panitia']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus panitia ini?')">

                                        <i class="bi bi-trash"></i>

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