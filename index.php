<?php
// Set header untuk memberitahu bahwa ini adalah JSON
header('Content-Type: application/json');

// Jika metode HTTP adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  	// Ambil data dari permintaan (misalnya, dari body POST)
	$data = json_decode(file_get_contents('php://input'), true);
  
    // Ambil data dari permintaan POST
    $longUrl = $data['long'];
    $shortCode = $data['code'];
  
    // Koneksi ke database (sesuaikan dengan informasi koneksi Anda)
    // Menyertakan file db_config.php
    require_once("db_config.php");

    // Buat koneksi
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }

    // Buat pernyataan prepared untuk menyimpan data ke tabel links
    $stmt = $conn->prepare("INSERT INTO links (short_code, long_url) VALUES (?, ?)");

    // Bind parameter ke pernyataan prepared
    $stmt->bind_param("ss", $shortCode, $longUrl);

    // Jalankan pernyataan prepared
    if ($stmt->execute()) {
        // URL Short
        $short_url = "https://phy.my.id/$shortCode";
      
        $response = ['message' => $short_url];
    } else {
        $response = ['message' => 'Error: ' . $stmt->error];
    }

    // Tutup pernyataan prepared
    $stmt->close();
  
    // Tutup koneksi database
    $conn->close();
} else {
    // Jika metode HTTP bukan POST, kirim respons metode tidak didukung
    $response = ['message' => 'Metode tidak didukung'];
}

// Ubah respons ke format JSON dan kirimkan kembali
echo json_encode($response);
?>
