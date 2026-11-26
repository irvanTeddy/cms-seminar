<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

// id seminar bersifat opsional: kalau ada, tampilkan peserta seminar itu saja;
// kalau tidak ada, tampilkan peserta dari semua seminar milik panitia
$id_seminar = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$seminar = null;

if ($id_seminar > 0) {

    /* ==========================
       CEK SEMINAR MILIK PANITIA
    ========================== */

    $cek = mysqli_query($koneksi, "
    SELECT *
    FROM seminar
    WHERE id_seminar='$id_seminar'
    AND id_panitia='$id_panitia'
    ");

    if (mysqli_num_rows($cek) == 0) {
        die("Seminar tidak ditemukan atau bukan milik Anda.");
    }

    $seminar = mysqli_fetch_assoc($cek);
}

$balik = 'peserta.php' . ($id_seminar > 0 ? '?id=' . $id_seminar : '');
$qs = $id_seminar > 0 ? 'id=' . $id_seminar . '&' : '';

/* ==========================
   VERIFIKASI
========================== */

if (isset($_GET['verifikasi'])) {

    $id = (int)$_GET['verifikasi'];

    mysqli_query($koneksi, "
    UPDATE pendaftaran
    JOIN seminar ON pendaftaran.id_seminar = seminar.id_seminar
    SET pendaftaran.status='Diverifikasi'
    WHERE pendaftaran.id_daftar='$id'
    AND seminar.id_panitia='$id_panitia'
    ");

    header("Location: " . $balik);
    exit;
}

/* ==========================
   TOLAK
========================== */

if (isset($_GET['tolak'])) {

    $id = (int)$_GET['tolak'];

    mysqli_query($koneksi, "
    UPDATE pendaftaran
    JOIN seminar ON pendaftaran.id_seminar = seminar.id_seminar
    SET pendaftaran.status='Ditolak'
    WHERE pendaftaran.id_daftar='$id'
    AND seminar.id_panitia='$id_panitia'
    ");

    header("Location: " . $balik);
    exit;
}

/* ==========================
   HAPUS PESERTA
========================== */

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi, "
    DELETE pendaftaran
    FROM pendaftaran
    JOIN seminar ON pendaftaran.id_seminar = seminar.id_seminar
    WHERE pendaftaran.id_daftar='$id'
    AND seminar.id_panitia='$id_panitia'
    ");

    header("Location: " . $balik);
    exit;
}

/* ==========================
   DATA PESERTA
========================== */

$filterSeminar = $id_seminar > 0 ? "AND pendaftaran.id_seminar='$id_seminar'" : "";

$data = mysqli_query($koneksi, "
SELECT
pendaftaran.*,
users.nama,
users.nim,
users.universitas,
users.email,
seminar.judul AS judul_seminar
FROM pendaftaran
JOIN users
ON pendaftaran.id_user = users.id_user
JOIN seminar
ON pendaftaran.id_seminar = seminar.id_seminar
WHERE seminar.id_panitia='$id_panitia'
$filterSeminar
ORDER BY pendaftaran.id_daftar DESC
");

// ---- Hanya untuk tampilan ----
$totalPeserta = mysqli_num_rows($data);
$namaUser = (string) $_SESSION['nama'];
$inisial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($namaUser, 0, 1)) : strtoupper(substr($namaUser, 0, 1));
$inisialOf = function ($s) {
    $s = trim((string) $s);
    if ($s === '') {
        return '?';
    }
    return function_exists('mb_substr') ? mb_strtoupper(mb_substr($s, 0, 1)) : strtoupper(substr($s, 0, 1));
};
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>Peserta Seminar</title>

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

        .btn-green {
            background: #c9f2dc;
            color: #0a4d30;
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

        .semi {
            display: inline-flex;
            align-items: flex-start;
            gap: 10px;
            max-width: 100%;
            margin-top: 18px;
            padding: 8px 16px;
            color: var(--ink);
            font-weight: 700;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 14px;
            box-shadow: 3px 3px 0 var(--ink);
        }

        .semi i {
            color: var(--blue);
            margin-top: 3px;
        }

        .semi span {
            min-width: 0;
            overflow-wrap: anywhere;
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
            margin-top: 20px;
        }

        .search {
            display: block;
            position: relative;
            max-width: 420px;
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

        .hitung {
            margin: 6px 0 0;
            font-size: .9rem;
            color: var(--muted);
        }

        /* ---------- Tabel peserta ---------- */
        .tbl-wrap {
            margin: 20px 6px 0 0;
            overflow-x: auto;
            background: #fff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
            -webkit-overflow-scrolling: touch;
        }

        .tbl {
            width: 100%;
            min-width: 1060px;
            border-collapse: collapse;
            font-size: .93rem;
        }

        .tbl thead th {
            padding: 14px 14px;
            text-align: left;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            white-space: nowrap;
            color: #fff;
            background: var(--ink);
        }

        .tbl tbody td {
            padding: 14px;
            vertical-align: middle;
            border-bottom: 2px dashed var(--line);
        }

        .tbl tbody tr:last-child td {
            border-bottom: 0;
        }

        .tbl tbody tr:hover td {
            background: #f8f9fd;
        }

        .col-no {
            width: 52px;
            font-weight: 800;
            color: var(--muted);
        }

        .nm {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 180px;
        }

        .pc-av {
            flex: none;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            font-size: 1rem;
            font-weight: 800;
            background: hsl(var(--h) 90% 88%);
            border: 2px solid var(--ink);
            border-radius: 50%;
        }

        .nm strong {
            font-weight: 800;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .col-seminar {
            min-width: 170px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .col-nim {
            font-weight: 700;
            white-space: nowrap;
        }

        .col-kampus {
            min-width: 150px;
            font-weight: 600;
        }

        .col-email {
            min-width: 190px;
            overflow-wrap: anywhere;
            color: var(--muted);
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

        .belum {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .88rem;
            font-weight: 700;
            white-space: nowrap;
            color: #b42318;
        }

        .tbl .btn {
            padding: 6px 12px;
            font-size: .85rem;
            white-space: nowrap;
            box-shadow: 2px 2px 0 var(--ink);
        }

        .tbl .btn:hover {
            box-shadow: 3px 3px 0 var(--ink);
        }

        .tbl .btn:active {
            box-shadow: 0 0 0 var(--ink);
        }

        .aksi {
            display: flex;
            gap: 8px;
            flex-wrap: nowrap;
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

            <?php if ($seminar) { ?>
                <a href="seminar.php" class="back"><i class="bi bi-arrow-left"></i> Seminar Saya</a>
            <?php } else { ?>
                <a href="dashboard.php" class="back"><i class="bi bi-arrow-left"></i> Dashboard</a>
            <?php } ?>

            <h1>Peserta Seminar</h1>

            <?php if ($seminar) { ?>
                <div class="semi">
                    <i class="bi bi-easel" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($seminar['judul']); ?></span>
                </div>
            <?php } ?>

        </div>
    </section>

    <main class="wrap">

        <section class="sec">

            <div class="sec-head">
                <h2>Daftar peserta</h2>
                <span class="count" aria-hidden="true"><?= $totalPeserta; ?></span>
            </div>

            <?php if ($totalPeserta > 0) { ?>

                <div class="controls">

                    <label class="search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="search" id="cari" placeholder="Cari nama, NIM, kampus, atau email" aria-label="Cari peserta">
                    </label>

                </div>

                <p class="hitung" id="hitung" aria-live="polite"></p>

                <div class="tbl-wrap">

                    <table class="tbl">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <?php if (!$seminar) { ?><th>Seminar</th><?php } ?>
                                <th>NIM</th>
                                <th>Asal Kampus</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Bukti Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="list">

                            <?php

                            $no = 1;

                            while ($row = mysqli_fetch_assoc($data)) {

                                $hue = crc32((string) $row['nama']) % 360;

                            ?>

                                <tr class="pc"
                                    style="--h:<?= $hue; ?>"
                                    data-status="<?= htmlspecialchars($row['status']); ?>"
                                    data-cari="<?= htmlspecialchars(strtolower($row['nama'] . ' ' . $row['nim'] . ' ' . $row['universitas'] . ' ' . $row['email'] . ' ' . $row['judul_seminar'])); ?>">

                                    <td class="col-no"><?= $no++; ?></td>

                                    <td>
                                        <div class="nm">
                                            <span class="pc-av" aria-hidden="true"><?= htmlspecialchars($inisialOf($row['nama'])); ?></span>
                                            <strong><?= htmlspecialchars($row['nama']); ?></strong>
                                        </div>
                                    </td>

                                    <?php if (!$seminar) { ?>
                                        <td class="col-seminar"><?= htmlspecialchars($row['judul_seminar']); ?></td>
                                    <?php } ?>

                                    <td class="col-nim"><?= htmlspecialchars($row['nim']); ?></td>

                                    <td class="col-kampus"><?= htmlspecialchars($row['universitas']); ?></td>

                                    <td class="col-email"><?= htmlspecialchars($row['email']); ?></td>

                                    <td>
                                        <?php

                                        if ($row['status'] == "Pending") {

                                            echo "<span class='badge b-pending'><i class='bi bi-clock-history'></i>Pending</span>";
                                        } elseif ($row['status'] == "Diverifikasi") {

                                            echo "<span class='badge b-ok'><i class='bi bi-check-circle-fill'></i>Diverifikasi</span>";
                                        } else {

                                            echo "<span class='badge b-no'><i class='bi bi-x-circle-fill'></i>Ditolak</span>";
                                        }

                                        ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($row['bukti_pembayaran'])) { ?>

                                            <a
                                                href="../uploads/bukti/<?= $row['bukti_pembayaran']; ?>"
                                                target="_blank"
                                                class="btn btn-white">

                                                <i class="bi bi-image"></i> Lihat Bukti

                                            </a>

                                        <?php } else { ?>

                                            <span class="belum">

                                                <i class="bi bi-exclamation-circle-fill"></i> Belum Upload

                                            </span>

                                        <?php } ?>
                                    </td>

                                    <td>
                                        <div class="aksi">

                                            <?php if ($row['status'] == "Pending") { ?>

                                                <a
                                                    href="?<?= $qs ?>verifikasi=<?= $row['id_daftar']; ?>"
                                                    class="btn btn-green">

                                                    <i class="bi bi-check-circle"></i>

                                                    Verifikasi

                                                </a>

                                                <a
                                                    href="?<?= $qs ?>tolak=<?= $row['id_daftar']; ?>"
                                                    class="btn btn-yellow">

                                                    <i class="bi bi-x-circle"></i>

                                                    Tolak

                                                </a>

                                            <?php } ?>

                                            <a
                                                href="?<?= $qs ?>hapus=<?= $row['id_daftar']; ?>"
                                                class="btn btn-red"
                                                onclick="return confirm('Yakin ingin menghapus peserta ini?')">

                                                <i class="bi bi-trash"></i>

                                                Hapus

                                            </a>

                                        </div>
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

                <div class="pesan" id="kosong" hidden>
                    <strong>Tidak ada peserta yang cocok.</strong>
                    Coba kata kunci lain.
                </div>

            <?php } else { ?>

                <div class="pesan">

                    <strong>Belum ada peserta yang mendaftar.</strong>

                    Peserta yang mendaftar akan muncul di sini.

                </div>

            <?php } ?>

        </section>

        <div class="bottom">
            <a href="<?= $seminar ? 'seminar.php' : 'dashboard.php'; ?>" class="btn btn-white">

                <i class="bi bi-arrow-left"></i> Kembali

            </a>
        </div>

    </main>

    <script>
        (function() {
            const items = [...document.querySelectorAll('tr.pc')];
            const cari = document.getElementById('cari');
            const kosong = document.getElementById('kosong');
            const hitung = document.getElementById('hitung');
            const tabel = document.querySelector('.tbl-wrap');

            if (!items.length) return;

            function terapkan() {
                const q = cari.value.trim().toLowerCase();
                let n = 0;
                items.forEach(i => {
                    const ok = !q || i.dataset.cari.includes(q);
                    i.hidden = !ok;
                    if (ok) n++;
                });
                kosong.hidden = n > 0;
                tabel.hidden = n === 0;
                hitung.textContent = 'Menampilkan ' + n + ' dari ' + items.length + ' peserta';
            }

            cari.addEventListener('input', terapkan);
            terapkan();
        })();
    </script>

</body>

</html>