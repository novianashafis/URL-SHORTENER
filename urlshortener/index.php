<?php
// Koneksi ke database
require_once("../db_config.php");

// Membuat koneksi baru menggunakan mysqli
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Menangani pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $long_url = $_POST["long_url"];

    // Menghasilkan kode pendek
    $short_code = substr(md5(time() . $long_url), 0, 4);

    // Buat pernyataan prepared untuk menyisipkan data ke dalam tabel links
    $stmt = $conn->prepare("INSERT INTO links (short_code, long_url) VALUES (?, ?)");
    $stmt->bind_param("ss", $short_code, $long_url);

    // Jalankan pernyataan prepared
    $stmt->execute();

    $short_url = "https://phy.my.id/$short_code";

    // Tutup pernyataan prepared
    $stmt->close();
    // Tutup koneksi database
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
</head>
<body>
    <h1>URL Shortener</h1>
    <h4>Project by Dijumper</h4>
    <form method="POST">
        <input type="url" name="long_url" placeholder="Masukkan URL panjang" required>
        <button type="submit">Shorten</button>
    </form>

    <?php if (isset($short_url)) { ?>
        <p>URL pendek Anda: <a href="<?php echo $short_url; ?>"><?php echo $short_url; ?></a></p>
    <?php } ?>
</body>
</html>

<?php

?>
