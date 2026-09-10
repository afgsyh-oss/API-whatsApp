<?php
session_start();

// Cek apakah siswa sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$nama  = htmlspecialchars($_SESSION["nama"]);
$email = htmlspecialchars($_SESSION["email"]);
$no_hp = htmlspecialchars($_SESSION["no_hp"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
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
            background: #ffff;
        }
        .container {
            width: 450px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .profile-card p {
            margin: 8px 0;
            color: #333;
            font-size: 14px;
        }
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 7px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-update {
            background: #222;
            color: white;
        }
        .btn-update:hover {
            background: #444;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #bd2130;
        }
        .btn-logout {
            background: #6c757d;
            color: white;
        }
        .btn-logout:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Dashboard Siswa</h2>

        <div class="profile-card">
            <p><strong>Nama:</strong> <?= $nama; ?></p>
            <p><strong>Email:</strong> <?= $email; ?></p>
            <p><strong>No. WhatsApp:</strong> <?= $no_hp; ?></p>
        </div>

        <div class="btn-group">
            <a href="update_profil.php" class="btn btn-update">✏️ Edit Profil</a>
            <a href="hapus_akun.php" class="btn btn-delete">🗑️ Hapus Akun</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
    </div>

</body>
</html>