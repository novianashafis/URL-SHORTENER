<?php
// Set header untuk memberitahu bahwa ini adalah JSON
header('Content-Type: application/json');

function isCodeExist($shortCode,$conn) {
  // Menyiapkan pernyataan SQL untuk memeriksa keberadaan short_code
  $stmt = $conn->prepare("SELECT 1 FROM links WHERE short_code = ?");
  $stmt->bind_param("s", $shortCode);
  $stmt->execute();
  $stmt->store_result();

  // Memeriksa apakah short_code sudah ada
  $exists = $stmt->num_rows > 0;
  $stmt->close();

  return $exists;
}

function getCode($longUrl, $conn) {
  $stmt = $conn->prepare("SELECT short_code FROM links WHERE long_url = ?");
  $stmt->bind_param("s", $longUrl);
  $stmt->execute();
  $stmt->bind_result($shortCode);
  $stmt->fetch();

  if (!$shortCode) return false;
  $stmt->close();

  return $shortCode;
}

function inputData($shortCode, $longUrl, $conn) {
  // Buat pernyataan prepared untuk menyimpan data ke tabel links
  $stmt = $conn->prepare("INSERT INTO links (short_code, long_url) VALUES (?, ?)");
  $stmt->bind_param("ss", $shortCode, $longUrl);

  $status = false;
  if ($stmt->execute()) {
    $status = true;
  }

  $stmt->close();

  return $status;
}

// Jika metode HTTP adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data dari permintaan (misalnya, dari body POST)
  $data = json_decode(file_get_contents('php://input'), true);

  // Ambil data dari permintaan POST
  $longUrl = $data['long'];
  $shortCode = $data['code'];

  // Menyertakan file db_config.php
  require_once("db_config.php");

  // Buat koneksi
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Cek koneksi
  if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
  }

  $codeExist = getCode($longUrl, $conn);
  $isCodeExist = isCodeExist($shortCode,$conn);

  if ($codeExist) {
    $short_url = "https://phy.my.id/$codeExist";
  } else {
    if ($isCodeExist) {
      $short_url = false;
    } else {
      $short_url = "https://phy.my.id/$shortCode";
      if (!inputData($shortCode,$longUrl,$conn)) {
        $response = ['message' => 'Error: ' . $stmt->error];
      }
    }
  }
  $response = ['message' => $short_url];
    
  // Tutup koneksi database
  $conn->close();
} else {
  // Jika metode HTTP bukan POST, kirim respons metode tidak didukung
  $response = ['message' => 'Metode tidak didukung'];
}

// Ubah respons ke format JSON dan kirimkan kembali
echo json_encode($response);
?>
