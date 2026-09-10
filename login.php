<?php

session_start();

require_once __DIR__ . "/../config/database.php";

// Pastikan request berasal dari form POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Metode request tidak valid.");
}

// Ambil data dari form
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

// Validasi
if ($email === "" || $password === "") {
    die("Email dan password wajib diisi.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

try {

    // Cari user berdasarkan email
    $stmt = $pdo->prepare(
        "SELECT id, nama, email, no_hp, password
         FROM users
         WHERE email = ?"
    );

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // User tidak ditemukan
    if (!$user) {
        die("
            <h2>Login Gagal</h2>
            <p style='color:red;'>❌ Email atau password salah.</p>
            <p><a href='../login.html'>← Kembali ke Login</a></p>
        ");
    }

    // Periksa password hasil password_hash() dari register.php
    if (!password_verify($password, $user["password"])) {
        die("
            <h2>Login Gagal</h2>
            <p style='color:red;'>❌ Email atau password salah.</p>
            <p><a href='../login.html'>← Kembali ke Login</a></p>
        ");
    }

    // Login berhasil: simpan data penting ke session
    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["nama"] = $user["nama"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["no_hp"] = $user["no_hp"];

    /*
     * Notifikasi WhatsApp
     * Untuk sementara kita kirim langsung menggunakan Fonnte,
     * dengan token yang sama seperti register.php.
     */
    // Ambil token dari fonnte.php yang sudah digunakan project.
    require_once __DIR__ . "/../config/fonnte.php";

    function kirimWhatsAppLogin($nomor, $pesan)
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
                "Authorization: " . $fonnte_token
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        $response = curl_exec($curl);
        $curlError = curl_error($curl);

        curl_close($curl);

        if ($response === false) {
            return [
                "status" => false,
                "reason" => "cURL Error: " . $curlError
            ];
        }

        return json_decode($response, true);
    }

    $pesan = "Halo {$user["nama"]} 👋\n\n"
           . "Login ke akun kamu berhasil.\n\n"
           . "Email: {$user["email"]}\n"
           . "Nomor WhatsApp: {$user["no_hp"]}\n\n"
           . "Jika kamu tidak merasa melakukan login, segera periksa akun kamu.";

    $waResponse = kirimWhatsAppLogin($user["no_hp"], $pesan);

    // Redirect ke dashboard setelah login
    header("Location: ../dashboard.php");
    exit;

} catch (PDOException $e) {

    die(
        "Terjadi kesalahan database: "
        . htmlspecialchars($e->getMessage())
    );
}
?>
