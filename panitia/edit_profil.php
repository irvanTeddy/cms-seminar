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

/* Statistik */

$totalSeminar = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM seminar
WHERE id_panitia='$id'
"))['total'];

$publish = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM seminar
WHERE id_panitia='$id'
AND status='Publish'
"))['total'];

$totalPeserta = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM pendaftaran
JOIN seminar
ON pendaftaran.id_seminar=seminar.id_seminar
WHERE seminar.id_panitia='$id'
"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM pendaftaran
JOIN seminar
ON pendaftaran.id_seminar=seminar.id_seminar
WHERE seminar.id_panitia='$id'
AND pendaftaran.status='Pending'
"))['total'];

// ---- Hanya untuk tampilan ----
$namaUser = isset($_SESSION['nama']) ? (string) $_SESSION['nama'] : 'Panitia';
$inisial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($namaUser, 0, 1)) : strtoupper(substr($namaUser, 0, 1));
$inisialPanitia = function_exists('mb_substr') ? mb_strtoupper(mb_substr((string) $p['nama_panitia'], 0, 1)) : strtoupper(substr((string) $p['nama_panitia'], 0, 1));
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>Profil Panitia</title>

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
            --green: #0b7a4b;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
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

        .wrap {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .narrow {
            max-width: 800px;
        }

        @media (min-width: 768px) {
            .wrap {
                padding: 0 32px;
            }
        }

        /* ---------- Tombol ---------- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            border: 2px solid var(--ink);
            font: inherit;
            font-size: .95rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 3px 3px 0 var(--ink);
            transition: transform .12s, box-shadow .12s, background .12s;
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

        .btn-yellow {
            background: var(--yellow);
            color: var(--ink);
        }

        .btn-white {
            background: #fff;
            color: var(--ink);
        }

        /* ---------- Header ---------- */
        .top {
            position: sticky;
            top: 0;
            z-index: 20;
            background: #fff;
            border-bottom: 2px solid var(--ink);
        }

        .top .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
            gap: 12px;
        }

        .top-left {
            display: flex;
            align-items: center;
            gap: 12px;
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
            border: 2px solid var(--ink);
            padding: 0 7px;
            border-radius: 7px;
            transform: rotate(-4deg);
        }

        .role {
            display: none;
            padding: 2px 12px;
            font-size: .8rem;
            font-weight: 700;
            background: #dfe5ff;
            color: var(--blue);
            border: 2px solid var(--ink);
            border-radius: 999px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .who {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            min-width: 0;
        }

        .avatar {
            flex: none;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            font-weight: 800;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 50%;
        }

        .who-name {
            display: none;
            max-width: 180px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .top .btn {
            padding: 8px 14px;
        }

        .top .btn span {
            display: none;
        }

        @media (min-width: 576px) {

            .who-name,
            .role {
                display: block;
            }

            .top .btn span {
                display: inline;
            }
        }

        /* ---------- Judul halaman ---------- */
        .hero {
            position: relative;
            overflow: hidden;
            color: #fff;
            background-color: var(--blue);
            background-image:
                linear-gradient(rgba(255, 255, 255, .11) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .11) 1px, transparent 1px);
            background-size: 36px 36px;
            border-bottom: 2px solid var(--ink);
            padding: 28px 0 40px;
        }

        .hero .wrap {
            position: relative;
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

        .hero :focus-visible {
            outline-color: var(--yellow);
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(2rem, 7vw, 3.2rem);
            font-weight: 800;
            font-stretch: 118%;
            line-height: 1.05;
            letter-spacing: -.028em;
        }

        .tile {
            display: none;
            position: absolute;
            place-items: center;
            border: 2px solid var(--ink);
            border-radius: 22px;
            box-shadow: 5px 5px 0 var(--ink);
        }

        .t1 {
            right: 90px;
            top: 6px;
            width: 100px;
            height: 100px;
            font-size: 2.9rem;
            background: var(--yellow);
            color: var(--ink);
            transform: rotate(-8deg);
        }

        .t2 {
            right: 0;
            top: 66px;
            width: 70px;
            height: 70px;
            font-size: 1.9rem;
            background: #fff;
            color: var(--blue);
            transform: rotate(8deg);
        }

        @media (min-width: 768px) {
            .hero {
                padding: 36px 0 52px;
            }

            .tile {
                display: grid;
            }
        }

        /* ---------- Kartu & form ---------- */
        .sec {
            padding: 36px 0 20px;
        }

        .card {
            margin-right: 6px;
            padding: 22px;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
        }

        @media (min-width: 576px) {
            .card {
                padding: 28px;
            }
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            padding: 12px 14px;
            font: inherit;
            font-weight: 500;
            color: var(--ink);
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 12px;
            box-shadow: 3px 3px 0 var(--ink);
        }

        .field textarea {
            resize: vertical;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 3px 3px 0 var(--blue);
        }

        .field input[type="file"] {
            padding: 10px;
        }

        .field input[type="file"]::file-selector-button {
            margin-right: 12px;
            padding: 7px 14px;
            font: inherit;
            font-size: .9rem;
            font-weight: 700;
            color: var(--ink);
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 8px;
            cursor: pointer;
        }

        .grid2 {
            display: grid;
            gap: 0 18px;
            grid-template-columns: minmax(0, 1fr);
        }

        @media (min-width: 576px) {
            .grid2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .poster-now {
            margin-bottom: 18px;
        }

        .poster-now img {
            display: block;
            max-width: 200px;
            height: auto;
            border: 2px solid var(--ink);
            border-radius: 12px;
            box-shadow: 3px 3px 0 var(--ink);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .foot {
            margin-top: 40px;
            padding: 22px 0 30px;
            font-size: .88rem;
            color: var(--muted);
            border-top: 2px dashed var(--line);
        }

        .pf-head {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
            margin-bottom: 22px;
        }

        .pf-av {
            width: 96px;
            height: 96px;
            display: grid;
            place-items: center;
            font-size: 2.6rem;
            font-weight: 800;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 50%;
            box-shadow: 4px 4px 0 var(--ink);
        }

        .pf-head h2 {
            margin: 6px 0 0;
            font-size: clamp(1.4rem, 5vw, 1.9rem);
            font-weight: 800;
            font-stretch: 115%;
            letter-spacing: -.02em;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 14px;
            font-size: .82rem;
            font-weight: 700;
            background: #c9f2dc;
            color: #0a4d30;
            border: 2px solid var(--ink);
            border-radius: 999px;
        }

        .det {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 16px;
            margin: 0;
            padding: 18px 0;
            border-top: 2px dashed var(--line);
            border-bottom: 2px dashed var(--line);
        }

        .det div {
            min-width: 0;
        }

        .det .wide {
            grid-column: 1 / -1;
        }

        .det dt {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0 0 1px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
        }

        .det dt i {
            color: var(--blue);
        }

        .det dd {
            margin: 0;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .stat4 {
            display: grid;
            gap: 14px;
            margin-top: 22px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        @media (min-width: 576px) {
            .stat4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .st {
            padding: 14px 10px;
            text-align: center;
            background: var(--paper);
            border: 2px solid var(--ink);
            border-radius: 14px;
        }

        .st b {
            display: block;
            font-size: 1.9rem;
            font-weight: 800;
            font-stretch: 120%;
            line-height: 1.1;
            letter-spacing: -.03em;
        }

        .st span {
            font-size: .88rem;
            font-weight: 600;
            color: var(--muted);
        }

        .st:nth-child(1) {
            background: #dfe5ff;
        }

        .st:nth-child(2) {
            background: #c9f2dc;
        }

        .st:nth-child(3) {
            background: #fff;
        }

        .st:nth-child(4) {
            background: var(--yellow);
        }

        .actions {
            justify-content: flex-end;
            margin-top: 24px;
        }

    </style>

</head>

<body>

    <header class="top">
        <div class="wrap">

            <div class="top-left">
                <a class="brand" href="dashboard.php">Seminar<span class="brand-it">IT</span></a>
                <span class="role">Panel Panitia</span>
            </div>

            <div class="top-right">

                <div class="who">
                    <span class="avatar" aria-hidden="true"><?= htmlspecialchars($inisial); ?></span>
                    <span class="who-name"><?= htmlspecialchars($namaUser); ?></span>
                </div>

                <a href="../logout.php" class="btn btn-white" aria-label="Logout">
                    <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                </a>

            </div>

        </div>
    </header>

    <section class="hero">
        <div class="wrap">

            <span class="tile t1" aria-hidden="true"><i class="bi bi-code-slash"></i></span>
            <span class="tile t2" aria-hidden="true"><i class="bi bi-terminal-fill"></i></span>

            <a href="dashboard.php" class="back"><i class="bi bi-arrow-left"></i> Dashboard</a>

            <h1>Profil Panitia</h1>

        </div>
    </section>

    <main class="wrap narrow">

        <section class="sec">

            <div class="card">

                <div class="pf-head">

                    <span class="pf-av" aria-hidden="true"><?= htmlspecialchars($inisialPanitia); ?></span>

                    <h2><?= htmlspecialchars($p['nama_panitia']); ?></h2>

                    <span class="badge"><i class="bi bi-patch-check-fill"></i><?= htmlspecialchars($p['status']); ?></span>

                </div>

                <dl class="det">

                    <div class="wide">
                        <dt><i class="bi bi-building"></i>Universitas</dt>
                        <dd><?= htmlspecialchars($p['universitas']); ?></dd>
                    </div>

                    <div>
                        <dt><i class="bi bi-envelope-fill"></i>Email</dt>
                        <dd><?= htmlspecialchars($p['email']); ?></dd>
                    </div>

                    <div>
                        <dt><i class="bi bi-telephone-fill"></i>No HP</dt>
                        <dd><?= htmlspecialchars($p['telepon']); ?></dd>
                    </div>

                </dl>

                <div class="stat4">

                    <div class="st">
                        <b><?= $totalSeminar ?></b>
                        <span>Seminar</span>
                    </div>

                    <div class="st">
                        <b><?= $publish ?></b>
                        <span>Publish</span>
                    </div>

                    <div class="st">
                        <b><?= $totalPeserta ?></b>
                        <span>Peserta</span>
                    </div>

                    <div class="st">
                        <b><?= $pending ?></b>
                        <span>Pending</span>
                    </div>

                </div>

                <div class="actions">

                    <a href="dashboard.php" class="btn btn-white">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>

                    <a href="edit_profil.php" class="btn btn-solid">
                        <i class="bi bi-pencil"></i>
                        Edit Profil
                    </a>

                </div>

            </div>

        </section>

        <div class="foot">© 2026 Irvan. All rights reserved.</div>

    </main>

</body>

</html>