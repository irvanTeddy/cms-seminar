<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

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

// ---- Hanya untuk tampilan ----
$totalSeminar = mysqli_num_rows($query);
$bulanID = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$namaUser = (string) $_SESSION['nama'];
$inisial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($namaUser, 0, 1)) : strtoupper(substr($namaUser, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>Seminar Informatika</title>

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

        .hero p {
            margin: 12px 0 0;
            max-width: 46ch;
            color: #e3e8ff;
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

        /* ---------- Kontrol ---------- */
        .sec {
            padding-top: 36px;
        }

        .sec-head {
            display: flex;
            align-items: center;
            gap: 12px;
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
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            display: grid;
            place-items: center;
            font-weight: 800;
            background: var(--yellow);
            border: 2px solid var(--ink);
            border-radius: 999px;
            transform: rotate(-6deg);
        }

        .controls {
            display: grid;
            gap: 14px;
            margin-top: 20px;
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
            border-color: var(--blue);
            box-shadow: 3px 3px 0 var(--blue);
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
            align-items: center;
            min-width: 0;
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

        .meta span {
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
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

        .bottom {
            margin-top: 44px;
            padding: 22px 0 36px;
            border-top: 2px dashed var(--line);
        }
    </style>

</head>

<body>

    <header class="top">
        <div class="wrap">

            <a class="brand" href="dashboard.php">Seminar<span class="brand-it">IT</span></a>

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

            <h1>Seminar Tersedia</h1>

            <p>Pilih seminar, workshop, atau pelatihan yang cocok untukmu, lalu daftar dalam satu klik.</p>

        </div>
    </section>

    <main class="wrap">

        <section class="sec">

            <div class="sec-head">
                <h2>Semua seminar</h2>
                <span class="count" aria-hidden="true"><?= $totalSeminar; ?></span>
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

                <?php while ($row = mysqli_fetch_assoc($query)) {

                    $ts  = strtotime($row['tanggal']);
                    $hue = crc32((string) $row['nama_kategori']) % 360;

                ?>

                    <article class="card"
                        style="--h:<?= $hue; ?>"
                        data-kategori="<?= htmlspecialchars($row['nama_kategori'] ?? ''); ?>"
                        data-cari="<?= htmlspecialchars(strtolower($row['judul'] . ' ' . ($row['pemateri'] ?? '') . ' ' . ($row['lokasi'] ?? '') . ' ' . $row['nama_panitia'] . ' ' . $row['nama_kategori'])); ?>">

                        <div class="media">

                            <?php

                            if ($row['poster'] != "") {

                            ?>

                                <img
                                    src="../uploads/poster/<?= $row['poster']; ?>"
                                    alt="Poster <?= htmlspecialchars($row['judul']); ?>"
                                    loading="lazy">

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

                            <?php if (isset($row['kuota'])) { ?>
                                <span class="kuota"><i class="bi bi-people-fill" aria-hidden="true"></i>Kuota <?= $row['kuota']; ?></span>
                            <?php } ?>

                        </div>

                        <div class="cbody">

                            <h3><?= $row['judul']; ?></h3>

                            <p class="by">Oleh <?= $row['nama_panitia']; ?></p>

                            <ul class="meta">

                                <?php if (!empty($row['pemateri'])) { ?>
                                    <li><i class="bi bi-person-video3" aria-hidden="true"></i><span title="<?= htmlspecialchars($row['pemateri']); ?>"><?= $row['pemateri']; ?></span></li>
                                <?php } ?>

                                <li><i class="bi bi-calendar3" aria-hidden="true"></i><span><?= date('j', $ts) . ' ' . $bulanID[(int) date('n', $ts)] . ' ' . date('Y', $ts); ?><?= !empty($row['jam']) ? ', ' . date('H:i', strtotime($row['jam'])) . ' WIB' : ''; ?></span></li>

                                <?php if (!empty($row['lokasi'])) { ?>
                                    <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><span title="<?= htmlspecialchars($row['lokasi']); ?>"><?= $row['lokasi']; ?></span></li>
                                <?php } ?>

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

                                <a
                                    href="daftar.php?id=<?= $row['id_seminar']; ?>"
                                    class="btn btn-solid">

                                    Daftar Seminar

                                </a>

                            </div>

                        </div>

                    </article>

                <?php } ?>

            </div>

            <div class="pesan" id="kosong" <?= $totalSeminar > 0 ? 'hidden' : ''; ?>>
                <strong id="kosong-judul"><?= $totalSeminar > 0 ? 'Tidak ada seminar yang cocok.' : 'Belum ada seminar yang dipublikasikan.'; ?></strong>
                <span id="kosong-isi"><?= $totalSeminar > 0 ? 'Coba kata kunci lain, atau tampilkan semua kategori.' : 'Seminar baru akan muncul di sini setelah panitia mempublikasikannya.'; ?></span>
                <?php if ($totalSeminar > 0) { ?>
                    <br>
                    <button type="button" class="btn btn-white" id="reset">Reset pencarian</button>
                <?php } ?>
            </div>

        </section>

        <div class="bottom">
            <a href="dashboard.php" class="btn btn-white">

                <i class="bi bi-arrow-left"></i> Kembali

            </a>
        </div>

    </main>

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
                if (items.length) {
                    kosong.hidden = n > 0;
                    hitung.textContent = 'Menampilkan ' + n + ' dari ' + items.length + ' seminar';
                }
            }

            cari.addEventListener('input', terapkan);

            if (reset) {
                reset.addEventListener('click', () => {
                    cari.value = '';
                    aktif = 'Semua';
                    tandai();
                    terapkan();
                    cari.focus();
                });
            }

            terapkan();
        })();
    </script>

</body>

</html>