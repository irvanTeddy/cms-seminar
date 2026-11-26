<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

/* ===========================
   HAPUS SEMINAR
=========================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "
    DELETE FROM seminar
    WHERE id_seminar='$id'
    AND id_panitia='$id_panitia'
    ");

    header("Location: seminar.php");
    exit;
}

/* ===========================
   DATA SEMINAR
=========================== */

$data = mysqli_query($koneksi, "
SELECT
seminar.*,
kategori.nama_kategori
FROM seminar

LEFT JOIN kategori
ON seminar.id_kategori=kategori.id_kategori

WHERE seminar.id_panitia='$id_panitia'

ORDER BY seminar.id_seminar DESC
");

// ---- Hanya untuk tampilan ----
$totalData = mysqli_num_rows($data);
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

    <title>Seminar Saya</title>

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

        .btn-yellow {
            background: var(--yellow);
            color: var(--ink);
        }

        .btn-white {
            background: #fff;
            color: var(--ink);
        }

        .btn-red {
            background: #ffd9d9;
            color: #7d1a1a;
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

        .hero p {
            margin: 12px 0 0;
            max-width: 46ch;
            color: #e3e8ff;
        }

        .hero .btn {
            margin-top: 22px;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
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

        .tab em {
            font-style: normal;
            font-size: .8rem;
            min-width: 22px;
            padding: 0 6px;
            text-align: center;
            color: var(--ink);
            background: var(--paper);
            border-radius: 999px;
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

        /* ---------- Daftar seminar ---------- */
        .list {
            display: grid;
            gap: 24px;
            margin-top: 20px;
            padding-right: 6px;
        }

        .sm {
            display: grid;
            gap: 14px 20px;
            align-items: start;
            padding: 14px;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
            grid-template-columns: minmax(0, 1fr);
            grid-template-areas: "p" "b" "x";
        }

        .sm-poster {
            grid-area: p;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border: 2px solid var(--ink);
            border-radius: 12px;
            background: hsl(var(--h) 80% 90%);
        }

        .sm-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ph {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            font-size: 2.2rem;
            color: hsl(var(--h) 60% 34%);
            background-image: radial-gradient(hsl(var(--h) 65% 76%) 1.6px, transparent 1.6px);
            background-size: 14px 14px;
        }

        .sm-body {
            grid-area: b;
            min-width: 0;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .chip {
            max-width: 100%;
            padding: 2px 12px;
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

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 12px;
            font-size: .8rem;
            font-weight: 700;
            border: 2px solid var(--ink);
            border-radius: 999px;
            white-space: nowrap;
        }

        .b-draft {
            background: #e3e6ee;
        }

        .b-pub {
            background: #c9f2dc;
            color: #0a4d30;
        }

        .b-done {
            background: var(--yellow);
        }

        .sm h3 {
            margin: 0 0 10px;
            font-size: 1.2rem;
            font-weight: 800;
            line-height: 1.28;
            letter-spacing: -.01em;
            overflow-wrap: anywhere;
        }

        .sm-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 20px;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: .92rem;
            color: #2d364b;
        }

        .sm-meta li {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .sm-meta i {
            color: var(--blue);
        }

        .sm-meta .free {
            color: var(--green);
            font-weight: 700;
        }

        .aksi {
            grid-area: x;
            display: flex;
            gap: 10px;
            padding-top: 14px;
            border-top: 2px dashed var(--line);
        }

        .aksi .btn {
            flex: 1 1 0;
            padding: 9px 10px;
            font-size: .9rem;
        }

        @media (min-width: 576px) {
            .sm {
                grid-template-columns: 150px minmax(0, 1fr);
                grid-template-areas: "p b" "x x";
            }

            .sm-poster {
                aspect-ratio: 4 / 3;
            }

            .aksi .btn {
                flex: 0 0 auto;
                padding: 9px 16px;
            }
        }

        @media (min-width: 992px) {
            .sm {
                grid-template-columns: 170px minmax(0, 1fr) 150px;
                grid-template-areas: "p b x";
                align-items: center;
                padding: 16px 20px 16px 16px;
            }

            .aksi {
                flex-direction: column;
                padding: 0 0 0 20px;
                border-top: 0;
                border-left: 2px dashed var(--line);
            }

            .aksi .btn {
                flex: none;
                width: 100%;
            }
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

            <h1>Seminar Saya</h1>

            <p>Atur seminar yang kamu selenggarakan: edit detail, lihat peserta, atau hapus seminar.</p>

            <a href="tambah_seminar.php" class="btn btn-yellow">

                <i class="bi bi-plus-circle"></i>

                Tambah Seminar

            </a>

        </div>
    </section>

    <main class="wrap">

        <section class="sec">

            <div class="sec-head">
                <h2>Daftar seminar</h2>
                <span class="count" aria-hidden="true"><?= $totalData; ?></span>
            </div>

            <?php if ($totalData > 0) { ?>

                <div class="controls">

                    <label class="search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="search" id="cari" placeholder="Cari judul atau kategori" aria-label="Cari seminar">
                    </label>

                    <div class="tabs" id="tabs" role="group" aria-label="Filter status"></div>

                </div>

                <p class="hitung" id="hitung" aria-live="polite"></p>

            <?php } ?>

            <div class="list" id="list">

                <?php

                $no = 1;

                while ($row = mysqli_fetch_assoc($data)) {

                    $ts  = strtotime($row['tanggal']);
                    $hue = crc32((string) $row['nama_kategori']) % 360;

                ?>

                    <article class="sm"
                        style="--h:<?= $hue; ?>"
                        data-status="<?= htmlspecialchars($row['status']); ?>"
                        data-cari="<?= htmlspecialchars(strtolower($row['judul'] . ' ' . $row['nama_kategori'])); ?>">

                        <div class="sm-poster">

                            <?php

                            if ($row['poster'] != "") {

                            ?>

                                <img
                                    src="../uploads/poster/<?= $row['poster']; ?>"
                                    alt="Poster <?= htmlspecialchars($row['judul']); ?>"
                                    loading="lazy">

                            <?php

                            } else {

                            ?>

                                <div class="ph"><i class="bi bi-image" aria-hidden="true"></i></div>

                            <?php

                            }

                            ?>

                        </div>

                        <div class="sm-body">

                            <div class="tags">

                                <?php

                                if ($row['status'] == "Draft") {

                                    echo "<span class='badge b-draft'><i class='bi bi-pencil-square'></i>Draft</span>";
                                } elseif ($row['status'] == "Publish") {

                                    echo "<span class='badge b-pub'><i class='bi bi-broadcast'></i>Publish</span>";
                                } else {

                                    echo "<span class='badge b-done'><i class='bi bi-check2-circle'></i>Selesai</span>";
                                }

                                ?>

                                <?php if (!empty($row['nama_kategori'])) { ?>
                                    <span class="chip"><?= $row['nama_kategori']; ?></span>
                                <?php } ?>

                            </div>

                            <h3>

                                <?= $row['judul']; ?>

                            </h3>

                            <ul class="sm-meta">

                                <li><i class="bi bi-calendar3" aria-hidden="true"></i><?= date('j', $ts) . ' ' . $bulanPendek[(int) date('n', $ts)] . ' ' . date('Y', $ts); ?></li>

                                <li>
                                    <i class="bi bi-cash-coin" aria-hidden="true"></i>
                                    <?php if ((float) $row['biaya'] == 0) { ?>
                                        <span class="free">Gratis</span>
                                    <?php } else { ?>
                                        Rp <?= number_format($row['biaya'], 0, ',', '.'); ?>
                                    <?php } ?>
                                </li>

                            </ul>

                        </div>

                        <div class="aksi">

                            <a
                                href="edit_seminar.php?id=<?= $row['id_seminar']; ?>"
                                class="btn btn-yellow">

                                <i class="bi bi-pencil"></i> Edit

                            </a>

                            <a
                                href="peserta.php?id=<?= $row['id_seminar']; ?>"
                                class="btn btn-solid">

                                <i class="bi bi-people"></i> Peserta

                            </a>

                            <a
                                onclick="return confirm('Hapus seminar?')"
                                href="?hapus=<?= $row['id_seminar']; ?>"
                                class="btn btn-red">

                                <i class="bi bi-trash"></i> Hapus

                            </a>

                        </div>

                    </article>

                <?php

                    $no++;
                }

                ?>

            </div>

            <?php if ($totalData == 0) { ?>

                <div class="pesan">
                    <strong>Belum ada seminar.</strong>
                    Buat seminar pertamamu, lalu publikasikan agar peserta bisa mendaftar.
                    <br>
                    <a href="tambah_seminar.php" class="btn btn-solid"><i class="bi bi-plus-circle"></i> Tambah Seminar</a>
                </div>

            <?php } else { ?>

                <div class="pesan" id="kosong" hidden>
                    <strong>Tidak ada seminar yang cocok.</strong>
                    Coba kata kunci lain, atau pilih status Semua.
                </div>

            <?php } ?>

        </section>

        <div class="bottom">
            <a href="dashboard.php" class="btn btn-white">

                <i class="bi bi-arrow-left"></i> Kembali

            </a>
        </div>

    </main>

    <script>
        (function() {
            const items = [...document.querySelectorAll('.sm')];
            const tabs = document.getElementById('tabs');
            const cari = document.getElementById('cari');
            const kosong = document.getElementById('kosong');
            const hitung = document.getElementById('hitung');
            let aktif = 'Semua';

            if (!items.length) return;

            const urut = ['Semua', 'Draft', 'Publish', 'Selesai'];
            const ada = new Set(items.map(i => i.dataset.status));
            const daftar = urut.filter(s => s === 'Semua' || ada.has(s));

            function jumlah(s) {
                return s === 'Semua' ? items.length : items.filter(i => i.dataset.status === s).length;
            }

            function tandai() {
                tabs.querySelectorAll('.tab').forEach(t =>
                    t.setAttribute('aria-pressed', t.dataset.s === aktif ? 'true' : 'false'));
            }

            function terapkan() {
                const q = cari.value.trim().toLowerCase();
                let n = 0;
                items.forEach(i => {
                    const ok = (aktif === 'Semua' || i.dataset.status === aktif) &&
                        (!q || i.dataset.cari.includes(q));
                    i.hidden = !ok;
                    if (ok) n++;
                });
                kosong.hidden = n > 0;
                hitung.textContent = 'Menampilkan ' + n + ' dari ' + items.length + ' seminar';
            }

            if (daftar.length > 2) {
                daftar.forEach(s => {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'tab';
                    b.dataset.s = s;
                    b.innerHTML = '<span></span><em>' + jumlah(s) + '</em>';
                    b.firstChild.textContent = s;
                    b.addEventListener('click', () => {
                        aktif = s;
                        tandai();
                        terapkan();
                    });
                    tabs.appendChild(b);
                });
                tandai();
            }

            cari.addEventListener('input', terapkan);
            terapkan();
        })();
    </script>

</body>

</html>