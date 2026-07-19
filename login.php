<?php
session_start();
require 'config/koneksi.php';

if (isset($_SESSION['login'])) {

    if ($_SESSION['role'] == "superadmin") {
        header("Location: admin/dashboard.php");
        exit;
    }

    if ($_SESSION['role'] == "panitia") {
        header("Location: panitia/dashboard.php");
        exit;
    }

    if ($_SESSION['role'] == "user") {
        header("Location: user/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login CMS Seminar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {

            background: #eef3fb;

        }

        .login-box {

            min-height: 100vh;

            display: flex;

            align-items: center;

        }

        .left-side {

            background: linear-gradient(135deg, #0d6efd, #4f8ef7);

            color: white;

            border-radius: 20px 0 0 20px;

            padding: 60px;

            min-height: 520px;

        }

        .right-side {

            background: white;

            border-radius: 0 20px 20px 0;

            padding: 60px;

            min-height: 520px;

        }

        .card-login {

            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);

            border: none;

            border-radius: 20px;

            overflow: hidden;

        }

        .form-control {

            height: 48px;

            border-radius: 10px;

        }

        .btn-login {

            height: 48px;

            border-radius: 10px;

            font-weight: bold;

        }

        .logo-icon {

            font-size: 90px;

            opacity: .2;

        }

        .input-group-text {

            border-radius: 10px 0 0 10px;

        }

        @media(max-width:768px) {

            .left-side {

                display: none;

            }

            .right-side {

                border-radius: 20px;

            }

        }
    </style>

</head>

<body>

    <div class="container login-box">

        <div class="row justify-content-center w-100">

            <div class="col-lg-10">

                <div class="card card-login">


                    <div class="row g-0">

                        <div class="col-lg-6 left-side d-flex flex-column justify-content-center">

                            <div>

                                <h1 class="fw-bold">

                                    CMS Seminar

                                </h1>

                                <p class="mt-3">

                                    Platform Seminar Informatika yang memudahkan mahasiswa mencari seminar, workshop, dan pelatihan dari berbagai panitia.

                                </p>

                                <i class="bi bi-mortarboard-fill logo-icon"></i>

                            </div>

                        </div>


                        <div class="col-lg-6 right-side">

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <a href="index.php"
                                    class="text-decoration-none text-muted small">

                                    <i class="bi bi-arrow-left"></i>

                                    Beranda

                                </a>

                                <span class="text-muted small">
                                    CMS Seminar
                                </span>

                            </div>

                            <h3 class="fw-bold text-center">

                                Login

                            </h3>

                            <p class="text-center text-muted mb-4">

                                Silakan masuk ke akun Anda

                            </p>

                            <form action="proses_login.php" method="POST">

                                <div class="mb-3">

                                    <label>Email</label>

                                    <div class="input-group">

                                        <span class="input-group-text">

                                            <i class="bi bi-envelope-fill"></i>

                                        </span>

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            placeholder="Masukkan email"
                                            required>

                                    </div>

                                </div>

                                <div class="mb-4">

                                    <label>Password</label>

                                    <div class="input-group">

                                        <span class="input-group-text">

                                            <i class="bi bi-lock-fill"></i>

                                        </span>

                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="Masukkan password"
                                            required>

                                    </div>

                                </div>

                                <button class="btn btn-primary btn-login w-100">

                                    <i class="bi bi-box-arrow-in-right"></i>

                                    Masuk

                                </button>

                            </form>

                            <hr>

                            <div class="text-center">

                                Belum punya akun?

                                <br><br>

                                <a href="register.php" class="btn btn-outline-primary">

                                    <i class="bi bi-person-plus-fill"></i>

                                    Daftar Sekarang

                                </a>

                            </div>

                        </div>


                    </div>


                </div>

            </div>

        </div>

    </div>

</body>

</html>