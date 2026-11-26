<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

// Hitung seminar
$qSeminar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM seminar WHERE id_panitia='$id_panitia'");
$totalSeminar = mysqli_fetch_assoc($qSeminar)['total'];

// Hitung peserta
$qPeserta = mysqli_query($koneksi, "
SELECT COUNT(*) as total
FROM pendaftaran
JOIN seminar ON pendaftaran.id_seminar=seminar.id_seminar
WHERE seminar.id_panitia='$id_panitia'
");

$totalPeserta = mysqli_fetch_assoc($qPeserta)['total'];

// ---- Hanya untuk tampilan ----
$namaUser = (string) $_SESSION['nama'];
$inisial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($namaUser, 0, 1)) : strtoupper(substr($namaUser, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>Dashboard Panitia</title>

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

        .btn-block {
            width: 100%;
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

        /* ---------- Sapaan ---------- */
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
            padding: 36px 0 84px;
        }

        .hero .wrap {
            position: relative;
        }

        .hero h1 {
            margin: 0;
            max-width: 18ch;
            font-size: clamp(1.9rem, 6.5vw, 3rem);
            font-weight: 800;
            font-stretch: 118%;
            line-height: 1.06;
            letter-spacing: -.028em;
            overflow-wrap: anywhere;
        }

        .hero p {
            margin: 14px 0 0;
            max-width: 44ch;
            color: #e3e8ff;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .hero :focus-visible {
            outline-color: var(--yellow);
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
            top: -4px;
            width: 110px;
            height: 110px;
            font-size: 3.2rem;
            background: var(--yellow);
            color: var(--ink);
            transform: rotate(-8deg);
        }

        .t2 {
            right: 0;
            top: 70px;
            width: 76px;
            height: 76px;
            font-size: 2rem;
            background: #fff;
            color: var(--blue);
            transform: rotate(8deg);
        }

        @media (min-width: 768px) {
            .hero {
                padding: 52px 0 104px;
            }

            .tile {
                display: grid;
            }
        }

        /* ---------- Kartu ringkasan ---------- */
        .stats {
            position: relative;
            z-index: 2;
            display: grid;
            gap: 18px;
            margin-top: -56px;
            grid-template-columns: minmax(0, 1fr);
            padding-right: 6px;
        }

        @media (min-width: 768px) {
            .stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 22px;
                margin-top: -64px;
            }
        }

        .sc {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 18px;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
        }

        .sc-top {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .sc-ic {
            flex: none;
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            border: 2px solid var(--ink);
            border-radius: 16px;
        }

        .ic-blue {
            background: #dfe5ff;
            color: var(--blue);
        }

        .ic-green {
            background: #c9f2dc;
            color: var(--green);
        }

        .sc-avatar {
            flex: none;
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 50%;
        }

        .sc b {
            display: block;
            font-size: 2.1rem;
            font-weight: 800;
            font-stretch: 120%;
            line-height: 1;
            letter-spacing: -.03em;
        }

        .sc b.nama {
            font-size: 1.25rem;
            line-height: 1.2;
            letter-spacing: -.01em;
            font-stretch: 100%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .sc span.lbl {
            display: block;
            margin-top: 4px;
            font-size: .92rem;
            font-weight: 600;
            color: var(--muted);
        }

        .sc-top>div {
            min-width: 0;
        }

        .foot {
            margin-top: 48px;
            padding: 22px 0 30px;
            font-size: .88rem;
            color: var(--muted);
            border-top: 2px dashed var(--line);
        }

        @media (prefers-reduced-motion: no-preference) {
            .sc {
                animation: naik .5s cubic-bezier(.2, .8, .2, 1) both;
            }

            .sc:nth-child(2) {
                animation-delay: .07s;
            }

            .sc:nth-child(3) {
                animation-delay: .14s;
            }

            @keyframes naik {
                from {
                    opacity: 0;
                    transform: translateY(16px);
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

            <h1>Halo, <?= htmlspecialchars($namaUser); ?></h1>

            <p>Kelola seminar dan pantau peserta yang mendaftar dari satu tempat.</p>

            <div class="hero-actions">
                <a href="seminar.php" class="btn btn-yellow"><i class="bi bi-plus-circle"></i> Tambah Seminar</a>
                <a href="peserta.php" class="btn btn-white"><i class="bi bi-people"></i> Peserta</a>
            </div>

        </div>
    </section>

    <main class="wrap">

        <div class="stats">

            <div class="sc">

                <div class="sc-top">
                    <div class="sc-ic ic-blue"><i class="bi bi-easel"></i></div>
                    <div>
                        <b><?= $totalSeminar; ?></b>
                        <span class="lbl">Seminar Saya</span>
                    </div>
                </div>

                <a href="seminar.php" class="btn btn-solid btn-block">Kelola Seminar</a>

            </div>

            <div class="sc">

                <div class="sc-top">
                    <div class="sc-ic ic-green"><i class="bi bi-people"></i></div>
                    <div>
                        <b><?= $totalPeserta; ?></b>
                        <span class="lbl">Total Peserta</span>
                    </div>
                </div>

                <a href="peserta.php" class="btn btn-white btn-block">Lihat Peserta</a>

            </div>

            <div class="sc">

                <div class="sc-top">
                    <div class="sc-avatar" aria-hidden="true"><?= htmlspecialchars($inisial); ?></div>
                    <div>
                        <b class="nama"><?= htmlspecialchars($namaUser); ?></b>
                        <span class="lbl">Panitia Seminar</span>
                    </div>
                </div>

                <a href="profil.php" class="btn btn-yellow btn-block">Profil</a>

            </div>

        </div>

        <div class="foot">© 2026 Irvan. All rights reserved.</div>

    </main>

</body>

</html>