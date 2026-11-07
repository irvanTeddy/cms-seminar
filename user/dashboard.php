<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id'];

// Total seminar yang diikuti
$q = mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE id_user='$id_user'
");

$total = mysqli_fetch_assoc($q)['total'];

// Jumlah yang sudah diverifikasi
$q2 = mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE id_user='$id_user'
AND status='Diverifikasi'
");

$verif = mysqli_fetch_assoc($q2)['total'];

// Jumlah pending
$q3 = mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM pendaftaran
WHERE id_user='$id_user'
AND status='Pending'
");

$pending = mysqli_fetch_assoc($q3)['total'];

// ---- Hanya untuk tampilan ----
$bulanPendek = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$namaUser = (string) $_SESSION['nama'];
$inisial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($namaUser, 0, 1)) : strtoupper(substr($namaUser, 0, 1));
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>Dashboard User</title>

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

        [hidden] {
            display: none !important;
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
            .who-name {
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

        /* ---------- Statistik ---------- */
        .stats {
            position: relative;
            z-index: 2;
            display: grid;
            gap: 16px;
            margin-top: -56px;
            grid-template-columns: minmax(0, 1fr);
        }

        @media (min-width: 768px) {
            .stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 22px;
                margin-top: -64px;
            }
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
        }

        .stat-ic {
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

        .ic-yellow {
            background: var(--yellow);
            color: var(--ink);
        }

        .stat b {
            display: block;
            font-size: 2.1rem;
            font-weight: 800;
            font-stretch: 120%;
            line-height: 1;
            letter-spacing: -.03em;
        }

        .stat span {
            display: block;
            margin-top: 4px;
            font-size: .92rem;
            font-weight: 600;
            color: var(--muted);
        }

        /* ---------- Bagian ---------- */
        .sec {
            padding-top: 48px;
        }

        .sec-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .sec h2 {
            margin: 0;
            font-size: clamp(1.4rem, 4.5vw, 1.9rem);
            font-weight: 800;
            font-stretch: 115%;
            letter-spacing: -.02em;
            line-height: 1.15;
        }

        .count {
            flex: none;
            min-width: 34px;
            height: 34px;
            padding: 0 9px;
            display: grid;
            place-items: center;
            font-size: .9rem;
            font-weight: 800;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 999px;
            transform: rotate(-6deg);
        }

        /* ---------- Menu ---------- */
        .menu {
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(0, 1fr);
            padding-right: 6px;
        }

        @media (min-width: 640px) {
            .menu {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 22px;
            }
        }

        .mi {
            position: relative;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            text-decoration: none;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
            transition: transform .15s, box-shadow .15s;
        }

        .mi:hover {
            transform: translate(-3px, -3px);
            box-shadow: 9px 9px 0 var(--ink);
        }

        .mi:active {
            transform: translate(4px, 4px);
            box-shadow: 2px 2px 0 var(--ink);
        }

        .mi-ic {
            flex: none;
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            font-size: 1.5rem;
            background: #fff;
            color: var(--ink);
            border: 2px solid var(--ink);
            border-radius: 15px;
        }

        .mi b {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .mi small {
            display: block;
            margin-top: 2px;
            font-size: .86rem;
            line-height: 1.35;
        }

        .mi .go {
            margin-left: auto;
            flex: none;
            font-size: 1.1rem;
        }

        .mi-blue {
            background: var(--blue);
            color: #fff;
        }

        .mi-blue small {
            color: #dfe5ff;
        }

        .mi-yellow {
            background: var(--yellow);
        }

        .mi-yellow small {
            color: #4a4320;
        }

        .mi-white {
            background: #fff;
        }

        .mi-white small {
            color: var(--muted);
        }

        @media (min-width: 640px) {
            .mi {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 20px 22px;
                min-height: 168px;
            }

            .mi .go {
                position: absolute;
                right: 18px;
                top: 18px;
                margin: 0;
            }

            .mi b {
                margin-top: 6px;
                font-size: 1.2rem;
            }
        }

        /* ---------- Riwayat ---------- */
        .tablewrap {
            overflow: hidden;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
        }

        .riwayat {
            width: 100%;
            border-collapse: collapse;
        }

        .riwayat thead th {
            padding: 13px 20px;
            text-align: left;
            font-size: .9rem;
            font-weight: 700;
            color: #fff;
            background: var(--ink);
        }

        .riwayat td {
            padding: 16px 20px;
            border-top: 1px solid var(--line);
            vertical-align: middle;
        }

        .riwayat tbody tr:first-child td {
            border-top: 0;
        }

        .riwayat tbody tr:hover {
            background: #f7f8fc;
        }

        .judul {
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .tgl {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            color: #2d364b;
        }

        .tgl i {
            color: var(--blue);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 12px;
            font-size: .82rem;
            font-weight: 700;
            border: 2px solid var(--ink);
            border-radius: 999px;
            white-space: nowrap;
        }

        .b-pending {
            background: var(--yellow);
        }

        .b-ok {
            background: #c9f2dc;
            color: #0a4d30;
        }

        .b-no {
            background: #ffd9d9;
            color: #7d1a1a;
        }

        .kosong {
            padding: 40px 20px !important;
            text-align: center;
            color: var(--muted);
        }

        .kosong .btn {
            margin-top: 14px;
        }

        .kosong strong {
            display: block;
            color: var(--ink);
            font-size: 1.1rem;
            margin-bottom: 2px;
        }

        @media (max-width: 639.98px) {
            .riwayat thead {
                position: absolute;
                width: 1px;
                height: 1px;
                overflow: hidden;
                clip: rect(0 0 0 0);
            }

            .riwayat,
            .riwayat tbody {
                display: block;
            }

            .riwayat tr {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 8px 12px;
                padding: 16px;
                border-top: 1px solid var(--line);
            }

            .riwayat tbody tr:first-child {
                border-top: 0;
            }

            .riwayat td {
                padding: 0;
                border: 0;
            }

            .riwayat td:first-child {
                width: 100%;
            }

            .riwayat td.kosong {
                width: 100%;
                padding: 32px 8px !important;
            }
        }

        .foot {
            margin-top: 64px;
            padding: 22px 0 30px;
            font-size: .88rem;
            color: var(--muted);
            border-top: 2px dashed var(--line);
        }

        @media (prefers-reduced-motion: no-preference) {
            .stat {
                animation: naik .5s cubic-bezier(.2, .8, .2, 1) both;
            }

            .stat:nth-child(2) {
                animation-delay: .07s;
            }

            .stat:nth-child(3) {
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

            <a class="brand" href="../index.php">Seminar<span class="brand-it">IT</span></a>

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

            <p>Pantau seminar yang kamu ikuti dan status verifikasinya di satu tempat.</p>

            <div class="hero-actions">
                <a href="seminar.php" class="btn btn-yellow"><i class="bi bi-search"></i> Lihat Seminar</a>
                <a href="seminar_saya.php" class="btn btn-white"><i class="bi bi-card-checklist"></i> Seminar Saya</a>
            </div>

        </div>
    </section>

    <main class="wrap">

        <div class="stats">

            <div class="stat">
                <div class="stat-ic ic-blue"><i class="bi bi-journal-bookmark"></i></div>
                <div>
                    <b><?= $total; ?></b>
                    <span>Seminar Diikuti</span>
                </div>
            </div>

            <div class="stat">
                <div class="stat-ic ic-green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <b><?= $verif; ?></b>
                    <span>Sudah Diverifikasi</span>
                </div>
            </div>

            <div class="stat">
                <div class="stat-ic ic-yellow"><i class="bi bi-clock-history"></i></div>
                <div>
                    <b><?= $pending; ?></b>
                    <span>Menunggu Verifikasi</span>
                </div>
            </div>

        </div>

        <section class="sec">

            <div class="sec-head">
                <h2>Menu</h2>
            </div>

            <div class="menu">

                <a href="seminar.php" class="mi mi-blue">
                    <span class="mi-ic"><i class="bi bi-search"></i></span>
                    <span>
                        <b>Lihat Seminar</b>
                        <small>Cari seminar yang sedang dibuka</small>
                    </span>
                    <i class="bi bi-arrow-up-right go" aria-hidden="true"></i>
                </a>

                <a href="seminar_saya.php" class="mi mi-yellow">
                    <span class="mi-ic"><i class="bi bi-card-checklist"></i></span>
                    <span>
                        <b>Seminar Saya</b>
                        <small>Lihat seminar yang sudah kamu daftar</small>
                    </span>
                    <i class="bi bi-arrow-up-right go" aria-hidden="true"></i>
                </a>

                <a href="../logout.php" class="mi mi-white">
                    <span class="mi-ic"><i class="bi bi-box-arrow-right"></i></span>
                    <span>
                        <b>Logout</b>
                        <small>Keluar dari akun ini</small>
                    </span>
                    <i class="bi bi-arrow-up-right go" aria-hidden="true"></i>
                </a>

            </div>

        </section>

        <section class="sec">

            <div class="sec-head">
                <h2>Riwayat Pendaftaran</h2>
                <span class="count" aria-hidden="true"><?= $total; ?></span>
            </div>

            <div class="tablewrap">

                <table class="riwayat">

                    <thead>

                        <tr>

                            <th>Judul Seminar</th>

                            <th>Tanggal</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $data = mysqli_query($koneksi, "
SELECT seminar.judul,
seminar.tanggal,
pendaftaran.status
FROM pendaftaran
JOIN seminar
ON pendaftaran.id_seminar=seminar.id_seminar
WHERE pendaftaran.id_user='$id_user'
ORDER BY pendaftaran.id_daftar DESC
");

                        if (mysqli_num_rows($data) == 0) {

                            echo "<tr><td colspan='3' class='kosong'><strong>Belum ada pendaftaran.</strong>Daftar seminar pertamamu dan statusnya akan muncul di sini.<br><a href='seminar.php' class='btn btn-solid'>Cari seminar</a></td></tr>";
                        } else {

                            while ($d = mysqli_fetch_assoc($data)) {

                                $tt = strtotime($d['tanggal']);

                                echo "<tr>";

                                echo "<td class='judul'>" . $d['judul'] . "</td>";

                                echo "<td><span class='tgl'><i class='bi bi-calendar3'></i>" . date('j', $tt) . ' ' . $bulanPendek[(int) date('n', $tt)] . ' ' . date('Y', $tt) . "</span></td>";

                                echo "<td>";

                                if ($d['status'] == "Pending") {

                                    echo "<span class='badge b-pending'><i class='bi bi-clock-history'></i>Pending</span>";
                                } elseif ($d['status'] == "Diverifikasi") {

                                    echo "<span class='badge b-ok'><i class='bi bi-check-circle-fill'></i>Diverifikasi</span>";
                                } else {

                                    echo "<span class='badge b-no'><i class='bi bi-x-circle-fill'></i>Ditolak</span>";
                                }

                                echo "</td>";

                                echo "</tr>";
                            }
                        }

                        ?>

                    </tbody>

                </table>

            </div>

        </section>

        <div class="foot">© 2026 Irvan. All rights reserved.</div>

    </main>

</body>

</html>