<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Akun</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000;
        }
        .container {
            width: 400px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #dc3545;
            margin-bottom: 15px;
        }
        p {
            color: #555;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 7px;
            background: #dc3545;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 12px;
        }
        button:hover {
            background: #bd2130;
        }
        .info a {
            color: #222;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>⚠️ Konfirmasi Hapus Akun</h2>
        <p>Apakah Anda yakin ingin menghapus akun ini? Data yang telah dihapus tidak dapat dikembalikan lagi.</p>

        <form action="api/hapus_akun.php" method="POST">
            <button type="submit" onclick="return confirm('Apakah Anda benar-benar yakin?')">Ya, Hapus Akun Saya</button>
        </form>

        <div class="info">
            <a href="dashboard.php">← Batalkan & Kembali ke Dashboard</a>
        </div>
    </div>

</body>
</html>