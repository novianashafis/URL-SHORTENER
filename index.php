<?php
// Koneksi ke database
$db_host = "localhost"; // Host database Anda
$db_name = "dijumper_shorten"; // Nama database Anda
$db_user = "USERNAME"; // Nama pengguna database Anda
$db_pass = "PASSWORD"; // Kata sandi database Anda

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Menangani pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $long_url = $_POST["long_url"];

    // Menghasilkan kode pendek
    $short_code = substr(md5(time() . $long_url), 0, 6);

    // Menyisipkan ke database
    $sql = "INSERT INTO links (short_code, long_url) VALUES ('$short_code', '$long_url')";
    mysqli_query($conn, $sql);

    $short_url = "http://dijumper.my.id/$short_code";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>URL Shortener</title>
</head>
<body>
    <h1>URL Shortener</h1>
    <form method="POST">
        <input type="url" name="long_url" placeholder="Masukkan URL panjang" required>
        <button type="submit">Shorten</button>
    </form>

    <?php if (isset($short_url)) { ?>
        <p>URL pendek Anda: <a href="<?php echo $short_url; ?>"><?php echo $short_url; ?></a></p>
    <?php } ?>
</body>
</html>