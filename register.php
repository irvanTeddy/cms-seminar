<?php
require 'config/koneksi.php';

if (isset($_POST['register'])) {

    $role = $_POST['role'];

    if ($role == "user") {

        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
        $universitas = mysqli_real_escape_string($koneksi, $_POST['universitas']);
        $email = mysqli_real_escape_string($koneksi, $_POST['email']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);

        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($cek) > 0) {

            echo "<script>alert('Email sudah digunakan');</script>";
        } else {

            mysqli_query($koneksi, "
            INSERT INTO users
            (nama,nim,universitas,email,password)
            VALUES
            ('$nama','$nim','$universitas','$email','$password')
            ");

            echo "<script>
            alert('Registrasi berhasil.');
            window.location='login.php';
            </script>";
        }
    } else {

        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_panitia']);
        $universitas = mysqli_real_escape_string($koneksi, $_POST['universitas_panitia']);
        $email = mysqli_real_escape_string($koneksi, $_POST['email_panitia']);
        $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password_panitia']);

        $cek = mysqli_query($koneksi, "SELECT * FROM panitia WHERE email='$email'");

        if (mysqli_num_rows($cek) > 0) {

            echo "<script>alert('Email sudah digunakan');</script>";
        } else {

            mysqli_query($koneksi, "
INSERT INTO panitia
(
    nama_panitia,
    universitas,
    email,
    password,
    telepon,
    status,
    created_at
)
VALUES
(
    '$nama',
    '$universitas',
    '$email',
    '$password',
    '$telepon',
    'Pending',
    NOW()
)
");

            echo "<script>
            alert('Pendaftaran panitia berhasil. Menunggu persetujuan Admin.');
            window.location='login.php';
            </script>";
        }
    }
}
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card shadow">


                    <div class="card-header bg-primary text-white">

                        <h4>Registrasi CMS Seminar</h4>

                    </div>

                    <div class="card-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">

                            <li class="nav-item">

                                <button class="nav-link active"
                                    data-bs-toggle="tab"
                                    data-bs-target="#user">

                                    Peserta

                                </button>

                            </li>

                            <li class="nav-item">

                                <button class="nav-link"
                                    data-bs-toggle="tab"
                                    data-bs-target="#panitia">

                                    Panitia

                                </button>

                            </li>

                        </ul>

                        <div class="tab-content mt-4">

                            <!-- USER -->

                            <div class="tab-pane fade show active" id="user">

                                <form method="POST">

                                    <input type="hidden" name="role" value="user">

                                    <input type="text" name="nama" class="form-control mb-3" placeholder="Nama" required>

                                    <input type="text" name="nim" class="form-control mb-3" placeholder="NIM" required>

                                    <input type="text" name="universitas" class="form-control mb-3" placeholder="Universitas" required>

                                    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

                                    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

                                    <button class="btn btn-primary w-100" name="register">

                                        Daftar Sebagai Peserta

                                    </button>

                                </form>

                            </div>

                            <!-- PANITIA -->

                            <div class="tab-pane fade" id="panitia">

                                <form method="POST">

                                    <input type="hidden" name="role" value="panitia">

                                    <input type="text" name="nama_panitia" class="form-control mb-3" placeholder="Nama Panitia / Organisasi" required>

                                    <input type="text" name="universitas_panitia" class="form-control mb-3" placeholder="Universitas" required>

                                    <input type="email" name="email_panitia" class="form-control mb-3" placeholder="Email" required>

                                    <input type="text" name="telepon" class="form-control mb-3" placeholder="Nomor HP" required>

                                    <input type="password" name="password_panitia" class="form-control mb-3" placeholder="Password" required>

                                    <button class="btn btn-success w-100" name="register">

                                        Daftar Sebagai Panitia

                                    </button>

                                </form>

                            </div>

                        </div>

                        <hr>

                        <div class="text-center">

                            Sudah punya akun?

                            <a href="login.php">

                                Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>