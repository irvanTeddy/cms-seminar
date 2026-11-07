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
    <meta name="theme-color" content="#2347ff">

    <title>Login CMS Seminar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --ink: #0f1b3d;
            --blue: #2347ff;
            --yellow: #ffe14d;
            --paper: #f3f4f9;
            --muted: #566075;
            --line: #d3d8e4;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        body {
            margin: 0;
            font-family: 'Archivo', system-ui, sans-serif;
            font-size: 16px;
            line-height: 1.55;
            color: var(--ink);
            background: var(--paper);
            -webkit-text-size-adjust: 100%;
        }

        a {
            color: inherit;
        }

        :focus-visible {
            outline: 3px solid var(--blue);
            outline-offset: 3px;
        }

        /* ---------- Tombol ---------- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 12px;
            border: 2px solid var(--ink);
            font: inherit;
            font-size: .98rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 3px 3px 0 var(--ink);
            transition: transform .12s, box-shadow .12s;
        }

        .btn:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--ink);
        }

        .btn:active {
            transform: translate(3px, 3px);
            box-shadow: 0 0 0 var(--ink);
        }

        .btn-solid {
            background: var(--blue);
            color: #fff;
        }

        .btn-line {
            background: #fff;
            color: var(--ink);
        }

        .btn-block {
            width: 100%;
            height: 52px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 1.2rem;
            font-weight: 800;
            font-stretch: 118%;
            letter-spacing: -.01em;
            text-decoration: none;
        }

        .brand-it {
            background: var(--yellow);
            color: var(--ink);
            border: 2px solid var(--ink);
            padding: 0 7px;
            border-radius: 7px;
            transform: rotate(-4deg);
        }

        /* ---------- Kerangka halaman ---------- */
        .auth {
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            grid-template-rows: auto 1fr;
        }

        .side {
            position: relative;
            overflow: hidden;
            color: #fff;
            padding: 18px 20px 24px;
            background-color: var(--blue);
            background-image:
                linear-gradient(rgba(255, 255, 255, .11) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .11) 1px, transparent 1px);
            background-size: 36px 36px;
            border-bottom: 2px solid var(--ink);
        }

        .side :focus-visible {
            outline-color: var(--yellow);
        }

        .side h1 {
            margin: 18px 0 0;
            max-width: 15ch;
            font-size: 1.7rem;
            font-weight: 800;
            font-stretch: 118%;
            line-height: 1.08;
            letter-spacing: -.025em;
        }

        .side p,
        .side-art {
            display: none;
        }

        .main {
            display: flex;
            flex-direction: column;
            padding: 22px 20px 36px;
        }

        .center {
            width: 100%;
            max-width: 460px;
            margin: auto;
        }

        .back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-weight: 600;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        .sheet {
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 20px;
            box-shadow: 6px 6px 0 var(--ink);
            padding: 26px 20px 24px;
        }

        .sheet h2 {
            margin: 0;
            font-size: 1.9rem;
            font-weight: 800;
            font-stretch: 115%;
            letter-spacing: -.025em;
            line-height: 1.1;
        }

        .sheet .sub {
            margin: 6px 0 24px;
            color: var(--muted);
        }

        /* ---------- Form ---------- */
        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: .9rem;
            font-weight: 700;
        }

        .input {
            position: relative;
        }

        .input .lead {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .input input {
            width: 100%;
            height: 50px;
            padding: 0 14px 0 44px;
            font: inherit;
            font-weight: 500;
            color: var(--ink);
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 12px;
        }

        .input input::placeholder {
            color: #7b859a;
        }

        .input input:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 3px 3px 0 var(--blue);
        }

        .input input.pw {
            padding-right: 50px;
        }

        .eye {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            font-size: 1.05rem;
            color: var(--ink);
            background: none;
            border: 0;
            border-radius: 9px;
            cursor: pointer;
        }

        .eye:hover {
            background: var(--yellow);
        }

        .pisah {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 16px;
            font-size: .9rem;
            color: var(--muted);
        }

        .pisah::before,
        .pisah::after {
            content: "";
            flex: 1;
            border-top: 2px dashed var(--line);
        }

        /* ---------- Layar lebar: dua kolom ---------- */
        @media (min-width: 576px) {
            .side {
                padding: 20px 32px 28px;
            }

            .main {
                padding: 32px 32px 44px;
            }

            .sheet {
                padding: 32px 30px 28px;
            }
        }

        @media (min-width: 992px) {
            .auth {
                grid-template-columns: minmax(0, 5fr) minmax(0, 6fr);
                grid-template-rows: none;
            }

            .side {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 40px 52px;
                border-bottom: 0;
                border-right: 2px solid var(--ink);
                position: sticky;
                top: 0;
                height: 100vh;
            }

            .side h1 {
                margin-top: 0;
                font-size: clamp(2.2rem, 3.3vw, 3.1rem);
            }

            .side p {
                display: block;
                margin: 20px 0 0;
                max-width: 40ch;
                color: #e3e8ff;
                font-size: 1.05rem;
            }

            .side-art {
                display: block;
                position: relative;
                height: 230px;
            }

            .main {
                padding: 40px 48px;
            }
        }

        /* ---------- Ikon coding ---------- */
        .tile {
            position: absolute;
            display: grid;
            place-items: center;
            border: 2px solid var(--ink);
            border-radius: 24px;
            box-shadow: 6px 6px 0 var(--ink);
        }

        .t1 {
            left: 4px;
            top: 36px;
            width: 140px;
            height: 140px;
            font-size: 4.2rem;
            background: var(--yellow);
            color: var(--ink);
            transform: rotate(-8deg);
        }

        .t2 {
            left: 176px;
            top: 0;
            width: 88px;
            height: 88px;
            font-size: 2.3rem;
            background: #fff;
            color: var(--blue);
            transform: rotate(8deg);
        }

        .t3 {
            left: 196px;
            top: 118px;
            width: 76px;
            height: 76px;
            font-size: 2rem;
            background: var(--ink);
            color: var(--yellow);
            transform: rotate(-4deg);
        }

        @media (prefers-reduced-motion: no-preference) {
            .sheet {
                animation: masuk .5s cubic-bezier(.2, .8, .2, 1) both;
            }

            @keyframes masuk {
                from {
                    opacity: 0;
                    transform: translateY(16px) rotate(-1deg);
                }

                to {
                    opacity: 1;
                    transform: none;
                }
            }
        }
    </style>

</head>

<body>

    <div class="auth">

        <aside class="side">

            <a class="brand" href="index.php">Seminar<span class="brand-it">IT</span></a>

            <div>
                <h1>Masuk dan ikut seminar berikutnya</h1>
                <p>
                    Platform Seminar Informatika yang memudahkan mahasiswa mencari seminar, workshop, dan pelatihan dari berbagai kampus di Yogyakarta.
                </p>
            </div>

            <div class="side-art" aria-hidden="true">
                <span class="tile t1"><i class="bi bi-code-slash"></i></span>
                <span class="tile t2"><i class="bi bi-terminal-fill"></i></span>
                <span class="tile t3"><i class="bi bi-braces"></i></span>
            </div>

        </aside>

        <main class="main">

            <div class="center">

                <a href="index.php" class="back">
                    <i class="bi bi-arrow-left"></i> Beranda
                </a>

                <div class="sheet">

                    <h2>Login</h2>
                    <p class="sub">Silakan masuk ke akun Anda</p>

                    <form action="proses_login.php" method="POST">

                        <div class="field">

                            <label for="email">Email</label>

                            <div class="input">
                                <i class="bi bi-envelope-fill lead" aria-hidden="true"></i>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    autocomplete="email"
                                    placeholder="Masukkan email"
                                    required>
                            </div>

                        </div>

                        <div class="field">

                            <label for="password">Password</label>

                            <div class="input">
                                <i class="bi bi-lock-fill lead" aria-hidden="true"></i>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="pw"
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    required>
                                <button type="button" class="eye" data-target="password" aria-label="Tampilkan password">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>

                        </div>

                        <button class="btn btn-solid btn-block">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Masuk
                        </button>

                    </form>

                    <div class="pisah">Belum punya akun?</div>

                    <a href="register.php" class="btn btn-line btn-block">
                        <i class="bi bi-person-plus-fill"></i>
                        Daftar sekarang
                    </a>

                </div>

            </div>

        </main>

    </div>

    <script>
        document.querySelectorAll('.eye').forEach(function(b) {
            b.addEventListener('click', function() {
                var i = document.getElementById(b.dataset.target);
                var tampil = i.type === 'password';
                i.type = tampil ? 'text' : 'password';
                b.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
                b.firstElementChild.className = tampil ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
            });
        });
    </script>

</body>

</html>