<?php
require '../templates/auth.php';
require '../config/koneksi.php';

// Hitung Statistik
$totalPanitia = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM panitia"));
$totalSeminar = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM seminar"));
$totalUser    = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));

include '../templates/header.php';
include '../templates/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-4">

            <h2 class="mb-4">
                Dashboard Super Admin
            </h2>

            <div class="row">

                <div class="col-md-4">

                    <div class="card border-0 shadow">

                        <div class="card-body">

                            <h5>Total Panitia</h5>

                            <h1 class="text-primary">

                                <?= $totalPanitia ?>

                            </h1>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card border-0 shadow">

                        <div class="card-body">

                            <h5>Total Seminar</h5>

                            <h1 class="text-success">

                                <?= $totalSeminar ?>

                            </h1>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card border-0 shadow">

                        <div class="card-body">

                            <h5>Total User</h5>

                            <h1 class="text-danger">

                                <?= $totalUser ?>

                            </h1>

                        </div>

                    </div>

                </div>

            </div>

            <hr class="my-4">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    Menu Cepat

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">

                            <a href="panitia.php" class="btn btn-primary w-100 p-3">

                                👨‍💼<br>

                                Data Panitia

                            </a>

                        </div>

                        <div class="col-md-3">

                            <a href="seminar.php" class="btn btn-success w-100 p-3">

                                🎓<br>

                                Data Seminar

                            </a>

                        </div>

                        <div class="col-md-3">

                            <a href="user.php" class="btn btn-warning w-100 p-3">

                                👤<br>

                                Data User

                            </a>

                        </div>

                        <div class="col-md-3">

                            <a href="../logout.php" class="btn btn-danger w-100 p-3">

                                🚪<br>

                                Logout

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow mt-4">

                <div class="card-header bg-dark text-white">

                    Panitia Terbaru

                </div>

                <div class="card-body">

                    <table class="table table-bordered table-hover">

                        <thead class="table-light">

                            <tr>

                                <th>No</th>

                                <th>Nama Panitia</th>

                                <th>Universitas</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $no = 1;

                            $query = mysqli_query($koneksi, "SELECT * FROM panitia ORDER BY id_panitia DESC LIMIT 5");

                            while ($row = mysqli_fetch_assoc($query)) {

                            ?>

                                <tr>

                                    <td><?= $no++ ?></td>

                                    <td><?= $row['nama_panitia'] ?></td>

                                    <td><?= $row['universitas'] ?></td>

                                    <td>

                                        <?php

                                        if ($row['status'] == "Aktif") {

                                            echo "<span class='badge bg-success'>Aktif</span>";
                                        } elseif ($row['status'] == "Pending") {

                                            echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                        } else {

                                            echo "<span class='badge bg-danger'>Nonaktif</span>";
                                        }

                                        ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../templates/footer.php'; ?>