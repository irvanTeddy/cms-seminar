<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cms_seminar2";

// Koneksi database MySQLi
$koneksi = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
