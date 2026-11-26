<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id'];

$data = mysqli_query($koneksi, "
SELECT
    pendaftaran.id_daftar,
    pendaftaran.status AS status_pendaftaran,

    seminar.id_seminar,
    seminar.judul,
    seminar.deskripsi,
    seminar.pemateri,
    seminar.tanggal,
    seminar.jam,
    seminar.lokasi,
    seminar.biaya,
    seminar.poster

FROM pendaftaran

JOIN seminar
ON pendaftaran.id_seminar = seminar.id_seminar

WHERE pendaftaran.id_user='$id_user'

ORDER BY pendaftaran.id_daftar DESC
");

// ---- Hanya untuk tampilan ----
$totalDaftar = mysqli_num_rows($data);
$bulanID = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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

        /* ---------- Bagian ---------- */
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

        .tabs {
            display: flex;
            gap: 10px;
            margin: 20px -4px 0 -2px;
            padding: 2px 4px 8px 2px;
            overflow-x: auto;
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
            background: var(--paper);
            border-radius: 999px;
            color: var(--ink);
        }

        .tab:hover {
            background: var(--yellow);
        }

        .tab[aria-pressed="true"] {
            background: var(--ink);
            color: #fff;
        }

        /* ---------- Kartu pendaftaran (gaya tiket) ---------- */
        .list {
            display: grid;
            gap: 28px;
            margin-top: 20px;
            padding-right: 6px;
        }

        .tk {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 6px 6px 0 var(--ink);
        }

        .tk-media {
            position: relative;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border-bottom: 2px dashed var(--ink);
            background: hsl(var(--h) 80% 90%);
        }

        .tk-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .ph small {
            display: block;
            margin-top: 4px;
            font-size: .85rem;
            font-weight: 600;
            text-align: center;
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

        .tk-body {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 18px 18px 20px;
            min-width: 0;
        }

        .tk h3 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -.015em;
            overflow-wrap: anywhere;
        }

        .badge {
            align-self: flex-start;
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

        .b-other {
            background: #e3e6ee;
        }

        .det {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            margin: 0;
            padding: 14px 0;
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

        .det .free {
            color: var(--green);
        }

        .desc summary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            cursor: pointer;
            list-style: none;
        }

        .desc summary::-webkit-details-marker {
            display: none;
        }

        .desc summary i {
            transition: transform .15s;
        }

        .desc[open] summary i {
            transform: rotate(180deg);
        }

        .desc summary:hover {
            color: var(--blue);
        }

        .desc-text {
            margin: 10px 0 0;
            color: #2d364b;
            overflow-wrap: anywhere;
        }

        .tk-act {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tk-act .btn {
            flex: 1 1 auto;
        }

        .tk-hint {
            margin: 0;
            font-size: .88rem;
            color: var(--muted);
        }

        @media (min-width: 576px) {
            .det {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .tk-act .btn {
                flex: 0 0 auto;
            }
        }

        @media (min-width: 860px) {
            .tk {
                grid-template-columns: 270px minmax(0, 1fr);
            }

            .tk-media {
                aspect-ratio: auto;
                min-height: 100%;
                border-bottom: 0;
                border-right: 2px dashed var(--ink);
            }

            .tk-media img {
                position: absolute;
                inset: 0;
            }

            .tk-body {
                padding: 22px 24px 24px;
            }

            .tk h3 {
                font-size: 1.5rem;
            }

            .det .wide {
                grid-column: auto;
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

            <h1>Seminar Saya</h1>

            <p>Semua seminar yang sudah kamu daftar, lengkap dengan status verifikasi dan bukti pendaftarannya.</p>

        </div>
    </section>

    <main class="wrap">

        <section class="sec">

            <div class="sec-head">
                <h2>Pendaftaranku</h2>
                <span class="count" aria-hidden="true"><?= $totalDaftar; ?></span>
            </div>

            <div class="tabs" id="tabs" role="group" aria-label="Filter status"></div>

            <div class="list" id="list">

                <?php

                if (mysqli_num_rows($data) == 0) {

                ?>

                    <div class="pesan" style="grid-column:1/-1">

                        <strong>Anda belum mendaftar seminar.</strong>

                        Pilih seminar yang kamu minati, lalu daftar. Statusnya akan muncul di sini.

                        <br>

                        <a href="seminar.php" class="btn btn-solid"><i class="bi bi-search"></i> Lihat Seminar</a>

                    </div>

                <?php

                }

                while ($d = mysqli_fetch_assoc($data)) {

                    $ts  = strtotime($d['tanggal']);
                    $hue = crc32((string) $d['judul']) % 360;

                ?>

                    <article class="tk" style="--h:<?= $hue; ?>" data-status="<?= htmlspecialchars($d['status_pendaftaran']); ?>">

                        <div class="tk-media">

                            <?php

                            if (!empty($d['poster'])) {

                            ?>

                                <img src="../uploads/poster/<?= $d['poster']; ?>"
                                    alt="Poster <?= htmlspecialchars($d['judul']); ?>"
                                    loading="lazy">

                            <?php

                            } else {

                            ?>

                                <div class="ph">
                                    <div>
                                        <i class="bi bi-image" aria-hidden="true"></i>
                                        <small>Poster Belum Tersedia</small>
                                    </div>
                                </div>

                            <?php } ?>

                            <div class="dbadge">
                                <b><?= date('d', $ts); ?></b>
                                <span><?= substr($bulanID[(int) date('n', $ts)], 0, 3); ?></span>
                            </div>

                        </div>

                        <div class="tk-body">

                            <?php

                            if ($d['status_pendaftaran'] == "Pending") {

                                echo "<span class='badge b-pending'><i class='bi bi-clock-history'></i>Pending</span>";
                            } elseif ($d['status_pendaftaran'] == "Diverifikasi") {

                                echo "<span class='badge b-ok'><i class='bi bi-check-circle-fill'></i>Diverifikasi</span>";
                            } elseif ($d['status_pendaftaran'] == "Ditolak") {

                                echo "<span class='badge b-no'><i class='bi bi-x-circle-fill'></i>Ditolak</span>";
                            } else {

                                echo "<span class='badge b-other'>" . htmlspecialchars($d['status_pendaftaran']) . "</span>";
                            }

                            ?>

                            <h3><?= $d['judul']; ?></h3>

                            <dl class="det">

                                <div>
                                    <dt><i class="bi bi-calendar3"></i>Tanggal</dt>
                                    <dd><?= date('j', $ts) . ' ' . $bulanID[(int) date('n', $ts)] . ' ' . date('Y', $ts); ?></dd>
                                </div>

                                <div>
                                    <dt><i class="bi bi-clock"></i>Jam</dt>
                                    <dd><?= $d['jam']; ?> WIB</dd>
                                </div>

                                <div>
                                    <dt><i class="bi bi-cash-coin"></i>Biaya</dt>
                                    <?php if ((float) $d['biaya'] == 0) { ?>
                                        <dd class="free">Gratis</dd>
                                    <?php } else { ?>
                                        <dd>Rp <?= number_format($d['biaya'], 0, ',', '.'); ?></dd>
                                    <?php } ?>
                                </div>

                                <div class="wide">
                                    <dt><i class="bi bi-geo-alt-fill"></i>Lokasi</dt>
                                    <dd><?= $d['lokasi']; ?></dd>
                                </div>

                                <div class="wide">
                                    <dt><i class="bi bi-person-video3"></i>Pemateri</dt>
                                    <dd><?= $d['pemateri']; ?></dd>
                                </div>

                            </dl>

                            <details class="desc">

                                <summary>
                                    Deskripsi Seminar
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </summary>

                                <p class="desc-text">

                                    <?= nl2br($d['deskripsi']); ?>

                                </p>

                            </details>

                            <?php
                            if ($d['status_pendaftaran'] == "Diverifikasi") {
                            ?>

                                <div class="tk-act">

                                    <a
                                        href="cetak_bukti.php?id=<?= $d['id_daftar']; ?>"
                                        target="_blank"
                                        class="btn btn-yellow">

                                        <i class="bi bi-file-earmark-arrow-down-fill"></i> Download Bukti PDF

                                    </a>

                                </div>

                            <?php
                            } elseif ($d['status_pendaftaran'] == "Pending") {
                            ?>

                                <p class="tk-hint"><i class="bi bi-info-circle"></i> Bukti PDF tersedia setelah pendaftaranmu diverifikasi.</p>

                            <?php
                            }
                            ?>

                        </div>

                    </article>

                <?php } ?>

            </div>

            <div class="pesan" id="kosong" hidden>
                <strong>Tidak ada pendaftaran dengan status ini.</strong>
                Pilih status lain untuk melihat pendaftaranmu.
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
            const items = [...document.querySelectorAll('.tk')];
            const tabs = document.getElementById('tabs');
            const kosong = document.getElementById('kosong');
            let aktif = 'Semua';

            if (!items.length) return;

            const urut = ['Semua', 'Pending', 'Diverifikasi', 'Ditolak'];
            const ada = new Set(items.map(i => i.dataset.status));
            const daftar = urut.filter(s => s === 'Semua' || ada.has(s))
                .concat([...ada].filter(s => !urut.includes(s)));

            function jumlah(s) {
                return s === 'Semua' ? items.length : items.filter(i => i.dataset.status === s).length;
            }

            function tandai() {
                tabs.querySelectorAll('.tab').forEach(t =>
                    t.setAttribute('aria-pressed', t.dataset.s === aktif ? 'true' : 'false'));
            }

            function terapkan() {
                let n = 0;
                items.forEach(i => {
                    const ok = aktif === 'Semua' || i.dataset.status === aktif;
                    i.hidden = !ok;
                    if (ok) n++;
                });
                kosong.hidden = n > 0;
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
        })();
    </script>

</body>

</html>