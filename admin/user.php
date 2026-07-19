<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

/* ======================
   HAPUS USER
====================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id'");

    header("Location: user.php");
    exit;
}

/* ======================
   DATA USER
====================== */

$data = mysqli_query($koneksi, "

SELECT
users.*,
COUNT(pendaftaran.id_daftar) AS total_seminar

FROM users

LEFT JOIN pendaftaran
ON users.id_user=pendaftaran.id_user

GROUP BY users.id_user

ORDER BY users.id_user DESC

");
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <span class="navbar-brand fw-bold">

                Super Admin

            </span>

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

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>

                <i class="bi bi-people-fill"></i>

                Data User

            </h3>

            <a href="dashboard.php" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

        </div>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>

                            <th>Nama</th>

                            <th>NIM</th>

                            <th>Universitas</th>

                            <th>Email</th>

                            <th>Jumlah Seminar</th>

                            <th width="120">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($u = mysqli_fetch_assoc($data)) {

                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td><?= htmlspecialchars($u['nama']); ?></td>

                                <td><?= htmlspecialchars($u['nim']); ?></td>

                                <td><?= htmlspecialchars($u['universitas']); ?></td>

                                <td><?= htmlspecialchars($u['email']); ?></td>

                                <td>

                                    <span class="badge bg-primary">

                                        <?= $u['total_seminar']; ?>

                                    </span>

                                </td>

                                <td>

                                    <a
                                        href="?hapus=<?= $u['id_user']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus user ini?')">

                                        <i class="bi bi-trash"></i>

                                        Hapus

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                        <?php if (mysqli_num_rows($data) == 0) { ?>

                            <tr>

                                <td colspan="7" class="text-center">

                                    Belum ada user.

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>