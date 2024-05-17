<?php
require_once("db_config.php");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$short_code = $_GET["code"]; // Kode pendek yang ingin Anda periksa

$sql = "SELECT long_url FROM links WHERE short_code = '$short_code'"; // Memilih URL panjang yang terkait dengan kode pendek
$result = $conn->query($sql); // Mengeksekusi kueri

if ($result->num_rows > 0) { // Jika URL panjang ditemukan
    $row = $result->fetch_assoc(); // Mengambil data dari hasil kueri

    $long_url = $row["long_url"]; // URL panjang yang ditemukan
    header("Location: $long_url"); // Mengalihkan pengguna ke URL panjang
    exit(); // Menghentikan eksekusi kode
} else { // Jika URL panjang tidak ditemukan
    echo "Short URL not found."; // Menampilkan pesan error
    exit(); // Menghentikan eksekusi kode
}
?>