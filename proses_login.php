<?php
session_start();
require 'config/koneksi.php';

$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

/* ==========================
   LOGIN SUPER ADMIN
========================== */
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email'");

if (mysqli_num_rows($query) > 0) {

    $admin = mysqli_fetch_assoc($query);

    if ($password == $admin['password']) {

        $_SESSION['login'] = true;
        $_SESSION['role'] = 'superadmin';
        $_SESSION['id'] = $admin['id_admin'];
        $_SESSION['nama'] = $admin['nama'];

        header("Location: admin/dashboard.php");
        exit;
    }
}

/* ==========================
   LOGIN PANITIA
========================== */

$query = mysqli_query($koneksi, "SELECT * FROM panitia
WHERE email='$email'
AND status='Aktif'");

if (mysqli_num_rows($query) > 0) {

    $panitia = mysqli_fetch_assoc($query);

    if ($password == $panitia['password']) {

        $_SESSION['login'] = true;
        $_SESSION['role'] = 'panitia';
        $_SESSION['id'] = $panitia['id_panitia'];
        $_SESSION['nama'] = $panitia['nama_panitia'];

        header("Location: panitia/dashboard.php");
        exit;
    }
}

/* ==========================
   LOGIN USER
========================== */

$query = mysqli_query($koneksi, "SELECT * FROM users
WHERE email='$email'");

if (mysqli_num_rows($query) > 0) {

    $user = mysqli_fetch_assoc($query);

    if ($password == $user['password']) {

        $_SESSION['login'] = true;
        $_SESSION['role'] = 'user';
        $_SESSION['id'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];

        header("Location: user/dashboard.php");
        exit;
    }
}

echo "<script>
alert('Email atau Password salah');
window.location='login.php';
</script>";
