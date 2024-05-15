<?php
$db_host = "localhost"; // Host database Anda
$db_name = "dijumper_shorten"; // Nama database Anda
$db_user = "USERNAME"; // Nama pengguna database Anda
$db_pass = "PASSWORD"; // Kata sandi database Anda

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$short_code = $_GET["code"]; // Kode pendek yang ingin Anda periksa

$sql = "SELECT long_url FROM links WHERE short_code = '$short_code'"; // Memilih URL panjang yang terkait dengan kode pendek
$result = mysqli_query($conn, $sql); // Mengeksekusi kueri

if (mysqli_num_rows($result) > 0) { // Jika URL panjang ditemukan
    $row = mysqli_fetch_assoc($result); // Mengambil data dari hasil kueri

    $long_url = $row["long_url"]; // URL panjang yang ditemukan
    header("Location: $long_url"); // Mengalihkan pengguna ke URL panjang
} else { // Jika URL panjang tidak ditemukan
    exit(); // Menghentikan eksekusi kode
}

echo "Short URL not found."; // Menampilkan pesan error
?>