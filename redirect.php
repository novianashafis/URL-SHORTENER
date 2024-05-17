<?php
require_once("db_config.php");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$short_code = $_GET["code"]; // Kode pendek yang ingin Anda periksa

// Buat pernyataan prepared untuk memilih URL panjang berdasarkan kode pendek
$stmt = $conn->prepare("SELECT long_url FROM links WHERE short_code = ?");
$stmt->bind_param("s", $short_code);

// Jalankan pernyataan prepared
$stmt->execute();

// Bind hasil dari kueri ke dalam variabel
$stmt->bind_result($long_url);

// Ambil hasil kueri
$stmt->fetch();

if ($long_url) { // Jika URL panjang ditemukan
    header("Location: $long_url"); // Mengalihkan pengguna ke URL panjang
    exit(); // Menghentikan eksekusi kode
} else { // Jika URL panjang tidak ditemukan
    echo "Short URL not found."; // Menampilkan pesan error
    exit(); // Menghentikan eksekusi kode
}

// Tutup pernyataan prepared
$stmt->close();
// Tutup koneksi database
$conn->close();
?>
