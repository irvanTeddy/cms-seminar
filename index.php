<?php
require 'config/koneksi.php';

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

WHERE seminar.status='Publish'

ORDER BY seminar.tanggal ASC
");

$totalSeminar = mysqli_num_rows($query);

// ---- Hanya untuk tampilan: tidak mengubah query / database ----
$bulanID = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hariID  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

// Satu putaran baca untuk: jumlah panitia dan seminar terdekat yang belum lewat
$panitiaSet = [];
$next = null;
$hariIni = date('Y-m-d');
mysqli_data_seek($query, 0);
while ($r = mysqli_fetch_assoc($query)) {
    if (!empty($r['nama_panitia'])) {
        $panitiaSet[$r['nama_panitia']] = true;
    }
    if ($next === null && $r['tanggal'] >= $hariIni) {
        $next = $r;
    }
}
$totalPanitia = count($panitiaSet);
?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>CMS Seminar Informatika</title>

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

        img {
            max-width: 100%;
            display: block;
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

        /* ---------- Tombol (gaya stiker: garis tebal + bayangan keras) ---------- */
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

        .btn-line {
            background: #fff;
            color: var(--ink);
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

        .top-nav {
            display: flex;
            gap: 10px;
        }

        .top-nav .btn {
            padding: 8px 15px;
        }

        /* ---------- Hero ---------- */
        .hero {
            color: #fff;
            background-color: var(--blue);
            background-image:
                linear-gradient(rgba(255, 255, 255, .11) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .11) 1px, transparent 1px);
            background-size: 36px 36px;
            border-bottom: 2px solid var(--ink);
            padding: 48px 0 64px;
            overflow: hidden;
        }

        .hero .wrap {
            display: grid;
            gap: 52px;
        }

        .hero h1 {
            margin: 0;
            max-width: 15ch;
            font-size: clamp(2.3rem, 8vw, 4rem);
            font-weight: 800;
            font-stretch: 120%;
            line-height: 1.03;
            letter-spacing: -.03em;
        }

        .hero-lead {
            margin: 20px 0 0;
            max-width: 46ch;
            font-size: 1.08rem;
            color: #e3e8ff;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 30px;
        }

        .hero :focus-visible {
            outline-color: var(--yellow);
        }

        .facts {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 34px 0 0;
            padding: 0;
            list-style: none;
        }

        .facts li {
            background: #fff;
            color: var(--ink);
            border: 2px solid var(--ink);
            border-radius: 999px;
            padding: 6px 16px;
            font-weight: 600;
            font-size: .95rem;
            box-shadow: 3px 3px 0 var(--ink);
        }

        .facts b {
            font-size: 1.1rem;
            font-weight: 800;
        }

        @media (min-width: 992px) {
            .hero {
                padding: 88px 0 100px;
            }

            .hero .wrap {
                grid-template-columns: 1.05fr .95fr;
                align-items: center;
                gap: 64px;
            }
        }

        /* ---------- Hiasan ikon coding di hero ---------- */
        .art {
            position: relative;
            min-height: 150px;
        }

        .tile {
            position: absolute;
            display: grid;
            place-items: center;
            border: 2px solid var(--ink);
            border-radius: 22px;
            box-shadow: 5px 5px 0 var(--ink);
        }

        .t1 {
            top: -30px;
            left: -4px;
            z-index: 1;
            width: 96px;
            height: 96px;
            font-size: 2.8rem;
            background: var(--yellow);
            color: var(--ink);
            transform: rotate(-8deg);
        }

        .t2 {
            right: 4px;
            bottom: -22px;
            z-index: 3;
            width: 66px;
            height: 66px;
            font-size: 1.7rem;
            background: #fff;
            color: var(--blue);
            transform: rotate(8deg);
        }

        .t3 {
            display: none;
            background: var(--ink);
            color: var(--yellow);
            transform: rotate(-5deg);
        }

        .ticket {
            z-index: 2;
        }

        @media (min-width: 576px) {
            .t1 {
                top: -38px;
                left: -22px;
                width: 120px;
                height: 120px;
                font-size: 3.5rem;
            }

            .t2 {
                right: -14px;
                bottom: -28px;
                width: 76px;
                height: 76px;
                font-size: 2rem;
            }

            .t3 {
                display: grid;
                left: -26px;
                bottom: 34px;
                z-index: 1;
                width: 62px;
                height: 62px;
                font-size: 1.6rem;
            }
        }

        /* Kalau tidak ada seminar mendatang: ikon jadi ilustrasi utama */
        .art.solo {
            min-height: 240px;
        }

        .art.solo .tile {
            border-radius: 28px;
            box-shadow: 7px 7px 0 var(--ink);
        }

        .art.solo .t1 {
            top: 10px;
            left: 8%;
            width: 150px;
            height: 150px;
            font-size: 4.6rem;
        }

        .art.solo .t2 {
            top: 0;
            right: 12%;
            bottom: auto;
            width: 96px;
            height: 96px;
            font-size: 2.6rem;
        }

        .art.solo .t3 {
            display: grid;
            right: 28%;
            bottom: 0;
            left: auto;
            width: 80px;
            height: 80px;
            font-size: 2.1rem;
        }

        @media (min-width: 992px) {
            .art.solo {
                min-height: 340px;
            }

            .art.solo .t1 {
                top: 40px;
                left: 12%;
                width: 220px;
                height: 220px;
                font-size: 6.6rem;
            }

            .art.solo .t2 {
                right: 10%;
                width: 120px;
                height: 120px;
                font-size: 3.2rem;
            }

            .art.solo .t3 {
                right: 30%;
                width: 96px;
                height: 96px;
                font-size: 2.6rem;
            }
        }

        /* ---------- Tiket seminar terdekat ---------- */
        .ticket {
            position: relative;
            display: grid;
            grid-template-columns: 1fr;
            background: #fff;
            color: var(--ink);
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 8px 8px 0 var(--ink);
        }

        .ticket-stub {
            display: flex;
            align-items: baseline;
            gap: 14px;
            padding: 16px 22px;
            background: var(--yellow);
            border-radius: 16px 16px 0 0;
            border-bottom: 2px dashed var(--ink);
        }

        .stub-day {
            font-size: 3.1rem;
            font-weight: 800;
            font-stretch: 125%;
            line-height: 1;
            letter-spacing: -.03em;
        }

        .stub-text {
            display: flex;
            flex-direction: column;
            font-weight: 700;
            line-height: 1.3;
        }

        .ticket-main {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 22px 22px 26px;
        }

        .ticket-note {
            margin: 0;
            font-size: .9rem;
            font-weight: 700;
            color: var(--blue);
        }

        .ticket h2 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.22;
            letter-spacing: -.015em;
        }

        .ticket-meta {
            display: grid;
            gap: 7px;
            margin: 4px 0 12px;
            padding: 0;
            list-style: none;
            font-size: .95rem;
            color: #2d364b;
        }

        .ticket-meta li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .ticket-meta i {
            color: var(--blue);
            margin-top: 2px;
        }

        .ticket .btn {
            align-self: flex-start;
        }

        .sticker {
            position: absolute;
            top: -26px;
            right: -6px;
            width: 86px;
            height: 86px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--ink);
            color: var(--yellow);
            border: 3px solid #fff;
            transform: rotate(10deg);
            line-height: 1.05;
            text-align: center;
        }

        .sticker small {
            font-size: .72rem;
            font-weight: 600;
            color: #fff;
        }

        .sticker b {
            font-size: 1.7rem;
            font-weight: 800;
            font-stretch: 115%;
        }

        @media (min-width: 576px) {
            .ticket {
                grid-template-columns: 150px 1fr;
            }

            .ticket-stub {
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                gap: 6px;
                padding: 24px 20px;
                border-radius: 16px 0 0 16px;
                border-bottom: 0;
                border-right: 2px dashed var(--ink);
            }

            .stub-day {
                font-size: 4rem;
            }

            .sticker {
                right: -18px;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .ticket {
                animation: tiket-masuk .6s cubic-bezier(.2, .8, .2, 1) both;
            }

            @keyframes tiket-masuk {
                from {
                    opacity: 0;
                    transform: translateY(20px) rotate(-2deg);
                }

                to {
                    opacity: 1;
                    transform: none;
                }
            }
        }

        /* ---------- Daftar seminar ---------- */
        .sec {
            padding: 56px 0 8px;
            scroll-margin-top: 68px;
        }

        .sec-head {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sec h2 {
            margin: 0;
            font-size: clamp(1.7rem, 5.5vw, 2.4rem);
            font-weight: 800;
            font-stretch: 118%;
            letter-spacing: -.025em;
            line-height: 1.1;
        }

        .count {
            flex: none;
            min-width: 40px;
            height: 40px;
            padding: 0 10px;
            display: grid;
            place-items: center;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 999px;
            font-weight: 800;
            transform: rotate(-6deg);
        }

        .controls {
            display: grid;
            gap: 14px;
            margin-top: 24px;
            grid-template-areas: "search" "tabs";
        }

        .search {
            grid-area: search;
            position: relative;
        }

        .search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink);
            pointer-events: none;
        }

        .search input {
            width: 100%;
            height: 50px;
            padding: 0 16px 0 44px;
            font: inherit;
            font-weight: 500;
            color: var(--ink);
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 12px;
            box-shadow: 3px 3px 0 var(--ink);
        }

        .search input::placeholder {
            color: #7b859a;
        }

        .search input:focus {
            outline: none;
            box-shadow: 3px 3px 0 var(--blue);
            border-color: var(--blue);
        }

        .tabs {
            grid-area: tabs;
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 2px 4px 8px 2px;
            margin: 0 -4px 0 -2px;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .tabs::-webkit-scrollbar {
            display: none;
        }

        .tabs:empty {
            display: none;
        }

        .tab {
            flex: none;
            padding: 8px 16px;
            font: inherit;
            font-size: .93rem;
            font-weight: 700;
            color: var(--ink);
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 999px;
            cursor: pointer;
            transition: background .12s;
        }

        .tab:hover {
            background: var(--yellow);
        }

        .tab[aria-pressed="true"] {
            background: var(--ink);
            color: #fff;
        }

        @media (min-width: 768px) {
            .controls {
                grid-template-columns: minmax(0, 1fr) 330px;
                grid-template-areas: "tabs search";
                align-items: start;
            }
        }

        .hitung {
            margin: 6px 0 0;
            font-size: .9rem;
            color: var(--muted);
        }

        /* ---------- Kartu seminar ---------- */
        .grid {
            display: grid;
            gap: 26px;
            grid-template-columns: 1fr;
            margin-top: 22px;
            padding-right: 6px;
        }

        @media (min-width: 640px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 992px) {
            .grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 6px 6px 0 var(--ink);
            transition: transform .15s, box-shadow .15s;
        }

        .card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 9px 9px 0 var(--blue);
        }

        .media {
            position: relative;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border-bottom: 2px solid var(--ink);
            background: hsl(var(--h) 80% 90%);
        }

        .media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .card:hover .media img {
            transform: scale(1.04);
        }

        .ph {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            font-size: 3rem;
            color: hsl(var(--h) 60% 34%);
            background-image: radial-gradient(hsl(var(--h) 65% 76%) 1.6px, transparent 1.6px);
            background-size: 16px 16px;
        }

        .dbadge {
            position: absolute;
            left: 12px;
            top: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 4px 11px 5px;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 12px;
            line-height: 1.05;
            box-shadow: 2px 2px 0 var(--ink);
        }

        .dbadge b {
            font-size: 1.5rem;
            font-weight: 800;
            font-stretch: 125%;
            letter-spacing: -.02em;
        }

        .dbadge span {
            font-size: .78rem;
            font-weight: 700;
        }

        .chip {
            position: absolute;
            right: 12px;
            top: 12px;
            max-width: 52%;
            padding: 3px 12px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            font-size: .8rem;
            font-weight: 700;
            color: hsl(var(--h) 75% 22%);
            background: hsl(var(--h) 90% 94%);
            border: 2px solid var(--ink);
            border-radius: 999px;
        }

        .cbody {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 14px 16px 16px;
        }

        .card h3 {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 800;
            line-height: 1.28;
            letter-spacing: -.01em;
            overflow-wrap: anywhere;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            overflow: hidden;
        }

        .by {
            margin: 2px 0 10px;
            font-size: .9rem;
            color: var(--muted);
        }

        .meta {
            display: grid;
            gap: 6px;
            margin: 0 0 14px;
            padding: 0;
            list-style: none;
            font-size: .92rem;
            color: #2d364b;
        }

        .meta li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .meta i {
            flex: none;
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            font-size: .8rem;
            color: var(--ink);
            background: hsl(var(--h) 90% 90%);
            border: 1.5px solid var(--ink);
            border-radius: 7px;
        }

        .meta li {
            align-items: center;
            min-width: 0;
        }

        .meta span {
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .kuota {
            position: absolute;
            left: 12px;
            bottom: 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 10px;
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            background: var(--ink);
            border-radius: 999px;
        }

        .cfoot {
            margin-top: auto;
            padding-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-top: 2px dashed var(--line);
        }

        .price small {
            display: block;
            font-size: .78rem;
            line-height: 1.2;
            color: var(--muted);
        }

        .price strong {
            font-size: 1.3rem;
            font-weight: 800;
            font-stretch: 112%;
        }

        .price .free {
            color: var(--green);
        }

        .pesan {
            margin-top: 22px;
            padding: 28px 24px;
            color: var(--muted);
            background: #fff;
            border: 2px dashed var(--ink);
            border-radius: 18px;
        }

        .pesan strong {
            display: block;
            margin-bottom: 4px;
            font-size: 1.15rem;
            color: var(--ink);
        }

        .pesan .btn {
            margin-top: 16px;
        }

        /* ---------- Footer ---------- */
        .foot {
            margin-top: 80px;
            padding: 52px 0 26px;
            color: #c3cbe0;
            background: var(--ink);
            border-top: 8px solid var(--yellow);
        }

        .foot .brand {
            color: #fff;
        }

        .foot :focus-visible {
            outline-color: var(--yellow);
        }

        .foot-grid {
            display: grid;
            gap: 32px;
        }

        .foot p {
            margin: 14px 0 0;
            max-width: 52ch;
        }

        .foot h3 {
            margin: 0 0 12px;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
        }

        .foot ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 10px;
        }

        .foot ul a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
        }

        .foot ul a:hover {
            text-decoration: underline;
        }

        .foot-bottom {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 6px 20px;
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, .18);
            font-size: .88rem;
        }

        @media (min-width: 768px) {
            .foot-grid {
                grid-template-columns: 1.4fr 1fr;
                gap: 60px;
            }
        }

        @media (max-width: 380px) {
            .top-nav .btn {
                padding: 7px 11px;
                font-size: .9rem;
            }

            .brand {
                font-size: 1.05rem;
            }
        }
    </style>

</head>

<body>

    <!-- NAVBAR -->
    <header class="top">
        <div class="wrap">

            <a class="brand" href="#">
                Seminar<span class="brand-it">IT</span>
            </a>

            <nav class="top-nav" aria-label="Akun">
                <a href="login.php" class="btn btn-line">Login</a>
                <a href="register.php" class="btn btn-solid">Daftar</a>
            </nav>

        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="wrap">

            <div>

                <h1>Cari seminar informatika di Yogyakarta</h1>

                <p class="hero-lead">
                    Seminar, workshop, dan pelatihan dari panitia mahasiswa Informatika di berbagai kampus Yogyakarta, dikumpulkan di satu tempat.
                </p>

                <div class="hero-actions">
                    <a href="#seminar" class="btn btn-yellow">Lihat semua seminar</a>
                    <a href="register.php" class="btn btn-white">Buat akun</a>
                </div>

                <ul class="facts">
                    <li><b><?= $totalSeminar ?></b> seminar dibuka</li>
                    <?php if ($totalPanitia > 0) { ?>
                        <li><b><?= $totalPanitia ?></b> panitia</li>
                    <?php } ?>
                </ul>

            </div>

            <div class="art<?= $next === null ? ' solo' : ''; ?>">

                <span class="tile t1" aria-hidden="true"><i class="bi bi-code-slash"></i></span>
                <span class="tile t2" aria-hidden="true"><i class="bi bi-terminal-fill"></i></span>
                <span class="tile t3" aria-hidden="true"><i class="bi bi-braces"></i></span>

                <?php if ($next !== null) {

                    $tn = strtotime($next['tanggal']);

                ?>

                    <article class="ticket" aria-label="Seminar berikutnya">

                        <div class="sticker" aria-label="Kuota <?= $next['kuota']; ?> peserta">
                            <small>Kuota</small>
                            <b><?= $next['kuota']; ?></b>
                        </div>

                        <div class="ticket-stub">
                            <span class="stub-day"><?= date('d', $tn); ?></span>
                            <span class="stub-text">
                                <span><?= $hariID[(int) date('w', $tn)]; ?></span>
                                <span><?= $bulanID[(int) date('n', $tn)] . ' ' . date('Y', $tn); ?></span>
                            </span>
                        </div>

                        <div class="ticket-main">

                            <p class="ticket-note">Seminar berikutnya</p>

                            <h2><?= $next['judul']; ?></h2>

                            <ul class="ticket-meta">
                                <li><i class="bi bi-person-video3"></i><span><?= $next['pemateri']; ?></span></li>
                                <li><i class="bi bi-clock"></i><span><?= date('H:i', strtotime($next['jam'])); ?> WIB</span></li>
                                <li><i class="bi bi-geo-alt-fill"></i><span><?= $next['lokasi']; ?></span></li>
                            </ul>

                            <a href="login.php" class="btn btn-solid">Daftar seminar ini</a>

                        </div>

                    </article>

                <?php } ?>

            </div>

        </div>
    </section>

    <!-- SEMINAR -->
    <main class="sec" id="seminar">
        <div class="wrap">

            <div class="sec-head">
                <h2>Semua seminar</h2>
                <span class="count" aria-hidden="true"><?= $totalSeminar ?></span>
            </div>

            <div class="controls">

                <label class="search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" id="cari" placeholder="Cari judul, pemateri, atau lokasi" aria-label="Cari seminar">
                </label>

                <div class="tabs" id="tabs" role="group" aria-label="Filter kategori"></div>

            </div>

            <p class="hitung" id="hitung" aria-live="polite"></p>

            <div class="grid" id="list">

                <?php

                if (mysqli_num_rows($query) == 0) {

                ?>

                    <div class="pesan" style="grid-column:1/-1">
                        <strong>Belum ada seminar yang dipublikasikan.</strong>
                        Seminar baru akan muncul di sini setelah panitia mempublikasikannya.
                    </div>

                <?php

                }

                mysqli_data_seek($query, 0);

                while ($row = mysqli_fetch_assoc($query)) {

                    $ts  = strtotime($row['tanggal']);
                    $hue = crc32((string) $row['nama_kategori']) % 360;

                ?>

                    <article class="card"
                        style="--h:<?= $hue; ?>"
                        data-kategori="<?= htmlspecialchars($row['nama_kategori'] ?? ''); ?>"
                        data-cari="<?= htmlspecialchars(strtolower($row['judul'] . ' ' . $row['pemateri'] . ' ' . $row['lokasi'] . ' ' . $row['nama_panitia'] . ' ' . $row['nama_kategori'])); ?>">

                        <div class="media">

                            <?php if ($row['poster'] != "") { ?>

                                <img src="uploads/poster/<?= $row['poster']; ?>" alt="Poster <?= htmlspecialchars($row['judul']); ?>" loading="lazy">

                            <?php } else { ?>

                                <div class="ph"><i class="bi bi-image" aria-hidden="true"></i></div>

                            <?php } ?>

                            <div class="dbadge">
                                <b><?= date('d', $ts); ?></b>
                                <span><?= substr($bulanID[(int) date('n', $ts)], 0, 3); ?></span>
                            </div>

                            <?php if (!empty($row['nama_kategori'])) { ?>
                                <span class="chip"><?= $row['nama_kategori']; ?></span>
                            <?php } ?>

                            <span class="kuota"><i class="bi bi-people-fill" aria-hidden="true"></i>Kuota <?= $row['kuota']; ?></span>

                        </div>

                        <div class="cbody">

                            <h3><?= $row['judul']; ?></h3>

                            <p class="by">Oleh <?= $row['nama_panitia']; ?></p>

                            <ul class="meta">
                                <li><i class="bi bi-person-video3" aria-hidden="true"></i><span title="<?= htmlspecialchars($row['pemateri']); ?>"><?= $row['pemateri']; ?></span></li>
                                <li><i class="bi bi-calendar3" aria-hidden="true"></i><span><?= date('j', $ts) . ' ' . substr($bulanID[(int) date('n', $ts)], 0, 3) . ' ' . date('Y', $ts) . ', ' . date('H:i', strtotime($row['jam'])); ?> WIB</span></li>
                                <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><span title="<?= htmlspecialchars($row['lokasi']); ?>"><?= $row['lokasi']; ?></span></li>
                            </ul>

                            <div class="cfoot">

                                <div class="price">
                                    <small>Biaya</small>
                                    <?php if ((float) $row['biaya'] == 0) { ?>
                                        <strong class="free">Gratis</strong>
                                    <?php } else { ?>
                                        <strong>Rp <?= number_format($row['biaya'], 0, ',', '.'); ?></strong>
                                    <?php } ?>
                                </div>

                                <a href="login.php" class="btn btn-solid">Daftar</a>

                            </div>

                        </div>

                    </article>

                <?php } ?>

            </div>

            <div class="pesan" id="kosong" hidden>
                <strong>Tidak ada seminar yang cocok.</strong>
                Coba kata kunci lain, atau tampilkan semua kategori.
                <br>
                <button type="button" class="btn btn-line" id="reset">Reset pencarian</button>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="foot">
        <div class="wrap">

            <div class="foot-grid">

                <div>
                    <a class="brand" href="#">Seminar<span class="brand-it">IT</span></a>
                    <p>
                        Platform pendaftaran seminar terpadu yang memudahkan mahasiswa menemukan berbagai event Informatika, workshop, dan pelatihan dari kampus-kampus di Yogyakarta.
                    </p>
                </div>

                <div>
                    <h3>Hubungi kami</h3>
                    <ul>
                        <li><a href="mailto:admin@seminar.com"><i class="bi bi-envelope-fill" aria-hidden="true"></i>admin@seminar.com</a></li>
                        <li><a href="tel:+6281234567890"><i class="bi bi-telephone-fill" aria-hidden="true"></i>+62 812-3456-7890</a></li>
                    </ul>
                </div>

            </div>

            <div class="foot-bottom">
                <span>© 2026 Tedz. All rights reserved.</span>
            </div>

        </div>
    </footer>

    <script>
        (function() {
            const items = [...document.querySelectorAll('.card')];
            const tabs = document.getElementById('tabs');
            const cari = document.getElementById('cari');
            const kosong = document.getElementById('kosong');
            const hitung = document.getElementById('hitung');
            const reset = document.getElementById('reset');
            let aktif = 'Semua';

            const daftar = ['Semua', ...new Set(items.map(i => i.dataset.kategori).filter(Boolean))];

            function tandai() {
                tabs.querySelectorAll('.tab').forEach(t =>
                    t.setAttribute('aria-pressed', t.dataset.k === aktif ? 'true' : 'false'));
            }

            if (daftar.length > 2) {
                daftar.forEach(k => {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'tab';
                    b.textContent = k;
                    b.dataset.k = k;
                    b.addEventListener('click', () => {
                        aktif = k;
                        tandai();
                        terapkan();
                    });
                    tabs.appendChild(b);
                });
                tandai();
            }

            function terapkan() {
                const q = cari.value.trim().toLowerCase();
                let n = 0;
                items.forEach(i => {
                    const ok = (aktif === 'Semua' || i.dataset.kategori === aktif) &&
                        (!q || i.dataset.cari.includes(q));
                    i.hidden = !ok;
                    if (ok) n++;
                });
                kosong.hidden = n > 0 || items.length === 0;
                hitung.textContent = items.length ?
                    'Menampilkan ' + n + ' dari ' + items.length + ' seminar' :
                    '';
            }

            cari.addEventListener('input', terapkan);

            reset.addEventListener('click', () => {
                cari.value = '';
                aktif = 'Semua';
                tandai();
                terapkan();
                cari.focus();
            });

            terapkan();
        })();
    </script>

</body>

</html>