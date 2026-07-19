<?php

require '../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM panitia
WHERE id_panitia='$id'");

header("Location: panitia.php");
