<?php

// Fix 1: Gunakan __DIR__ agar path database terhubung dengan presisi
require_once __DIR__ . "/../config/database.php";

define('FONNTE_TOKEN', 'BgVWkRJGKKNa6ztMg33K');

function kirimWhatsApp($nomor, $pesan)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "target" => $nomor,
            "message" => $pesan
        ],
        CURLOPT_HTTPHEADER => [
            "Authorization: " . FONNTE_TOKEN
        ],
        // Fix 2: Bypassing SSL khusus untuk pengujian di Localhost (XAMPP)
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($response === false) {
        return ['status' => false, 'reason' => 'cURL Error: ' . $curlError];
    }

    return json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Metode request tidak valid.");
}

// Data input
$nama = trim($_POST["nama"] ?? "");
$email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
$no_hp = trim($_POST["no_hp"] ?? "");
$password = $_POST["password"] ?? "";

// Validasi input
if ($nama === "" || $email === "" || $no_hp === "" || $password === "") {
    die("Semua data wajib diisi.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

if (strlen($password) < 6) {
    die("Password minimal 6 karakter.");
}

// Format nomor HP (08xx -> 628xx)
$no_hp = preg_replace('/[^0-9]/', '', $no_hp);

if (str_starts_with($no_hp, '0')) {
    $no_hp = "62" . substr($no_hp, 1);
}

if (strlen($no_hp) < 10 || strlen($no_hp) > 15) {
    die("Nomor WhatsApp tidak valid.");
}

try {
    // Cek duplikasi email & nomor HP
    $stmt = $pdo->prepare("SELECT email, no_hp FROM users WHERE email = ? OR no_hp = ?");
    $stmt->execute([$email, $no_hp]);
    $userExisted = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userExisted) {
    echo "<h2>Registrasi Gagal</h2>";
    if ($userExisted['email'] === $email) {
        echo "<p style='color: red;'>❌ Email <b>" . htmlspecialchars($email) . "</b> sudah terdaftar.</p>";
    } else if ($userExisted['no_hp'] === $no_hp) {
        echo "<p style='color: red;'>❌ Nomor WhatsApp <b>" . htmlspecialchars($no_hp) . "</b> sudah terdaftar.</p>";
    }
    echo "<p><a href='../index.html'>← Kembali ke Form Registrasi</a></p>";
    exit; // Menghentikan eksekusi dengan rapi
}

    // Hash password & Simpan Ke Database
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nama, email, no_hp, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $email, $no_hp, $password_hash]);

    // Kirim notifikasi WA
    $pesan = "Halo $nama 👋\n\nRegistrasi akun kamu berhasil.\n\nNama: $nama\nEmail: $email\nNomor WhatsApp: $no_hp\n\nTerima kasih.";
    $waResponse = kirimWhatsApp($no_hp, $pesan);

    // Tampilan Hasil
    echo "<h2>Registrasi Berhasil!</h2>";
    echo "<p>Nama: " . htmlspecialchars($nama) . "</p>";
    echo "<p>Email: " . htmlspecialchars($email) . "</p>";
    echo "<p>Nomor WhatsApp: " . htmlspecialchars($no_hp) . "</p>";

    // Debugging respons Fonnte langsung di layar
    if (isset($waResponse['status']) && $waResponse['status'] === true) {
        echo "<p style='color: green;'><b>✅ Notifikasi WA berhasil dikirim!</b></p>";
    } else {
        $detailError = $waResponse['reason'] ?? ($waResponse['message'] ?? 'Unknown Error');
        echo "<p style='color: red;'><b>❌ Gagal Kirim WA:</b> " . htmlspecialchars($detailError) . "</p>";
    }

} catch (PDOException $e) {
    die("Terjadi kesalahan database: " . htmlspecialchars($e->getMessage()));
}