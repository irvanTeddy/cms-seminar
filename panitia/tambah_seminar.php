<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'panitia') {
    header("Location: ../login.php");
    exit;
}

require '../config/koneksi.php';

$id_panitia = $_SESSION['id'];

/* ===========================
   AMBIL KATEGORI
=========================== */

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

/* ===========================
   SIMPAN DATA
=========================== */

if (isset($_POST['simpan'])) {

    $id_kategori = $_POST['id_kategori'];
    $judul       = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $pemateri    = mysqli_real_escape_string($koneksi, $_POST['pemateri']);
    $tanggal     = $_POST['tanggal'];
    $jam         = $_POST['jam'];
    $lokasi      = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $kuota       = $_POST['kuota'];
    $biaya       = $_POST['biaya'];

    $poster = "";

    if ($_FILES['poster']['name'] != "") {

        $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);

        $poster = time() . "." . $ext;

        move_uploaded_file(
            $_FILES['poster']['tmp_name'],
            "../uploads/poster/" . $poster
        );
    }

    mysqli_query($koneksi, "
    INSERT INTO seminar
    (
        id_panitia,
        id_kategori,
        judul,
        deskripsi,
        pemateri,
        tanggal,
        jam,
        lokasi,
        kuota,
        poster,
        biaya,
        status,
        created_at
    )
    VALUES
    (
        '$id_panitia',
        '$id_kategori',
        '$judul',
        '$deskripsi',
        '$pemateri',
        '$tanggal',
        '$jam',
        '$lokasi',
        '$kuota',
        '$poster',
        '$biaya',
        'Draft',
        NOW()
    )
    ");

    echo "<script>
    alert('Seminar berhasil ditambahkan dan menunggu Publish Admin');
    window.location='seminar.php';
    </script>";
}

// ---- Hanya untuk tampilan ----
$namaUser = isset($_SESSION['nama']) ? (string) $_SESSION['nama'] : 'Panitia';
$inisial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($namaUser, 0, 1)) : strtoupper(substr($namaUser, 0, 1));
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2347ff">

    <title>Tambah Seminar</title>

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

            <a href="seminar.php" class="back"><i class="bi bi-arrow-left"></i> Seminar Saya</a>

            <h1>Tambah Seminar</h1>

        </div>
    </section>

    <main class="wrap narrow">

        <section class="sec">

            <div class="card">

                <form method="POST" enctype="multipart/form-data">

                    <div class="field">

                        <label for="id_kategori">Kategori</label>

                        <select name="id_kategori" id="id_kategori" required>

                            <option value="">-- Pilih Kategori --</option>

                            <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                                <option value="<?= $k['id_kategori']; ?>">

                                    <?= htmlspecialchars($k['nama_kategori']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="field">
                        <label for="judul">Judul Seminar</label>
                        <input type="text" id="judul" name="judul" required>
                    </div>

                    <div class="field">
                        <label for="pemateri">Pemateri</label>
                        <input type="text" id="pemateri" name="pemateri" required>
                    </div>

                    <div class="field">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>
                    </div>

                    <div class="grid2">

                        <div class="field">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" required>
                        </div>

                        <div class="field">
                            <label for="jam">Jam</label>
                            <input type="time" id="jam" name="jam" required>
                        </div>

                    </div>

                    <div class="field">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" required>
                    </div>

                    <div class="grid2">

                        <div class="field">
                            <label for="kuota">Kuota</label>
                            <input type="number" id="kuota" name="kuota" required>
                        </div>

                        <div class="field">
                            <label for="biaya">Biaya</label>
                            <input type="number" id="biaya" name="biaya" value="0">
                        </div>

                    </div>

                    <div class="field">
                        <label for="poster">Poster</label>
                        <input type="file" id="poster" name="poster" required>
                    </div>

                    <div class="actions">

                        <button class="btn btn-solid" name="simpan">
                            <i class="bi bi-check-circle"></i>
                            Simpan Seminar
                        </button>

                        <a href="seminar.php" class="btn btn-white">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </section>

        <div class="foot">© 2026 Irvan. All rights reserved.</div>

    </main>

</body>

</html>