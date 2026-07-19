<?php

require '../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($koneksi, "UPDATE panitia
SET status='Aktif'
WHERE id_panitia='$id'");

header("Location: panitia.php");
