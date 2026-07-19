<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "seminar_db";

// Koneksi database MySQLi
$koneksi = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>