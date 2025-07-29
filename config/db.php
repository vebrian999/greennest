<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "greennest_db";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi database gagal.");
}
?>
