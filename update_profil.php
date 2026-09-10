<?php
session_start();

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
    <title>Update Profil</title>
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
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 14px;
        }
        input:read-only {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 7px;
            background: #222;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }
        button:hover {
            background: #444;
        }
        .info {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }
        .info a {
            color: #222;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Update Profil</h2>

        <form action="api/update_profil.php" method="POST">
            <div class="form-group">
                <label for="email">Email (Tidak dapat diubah)</label>
                <input type="email" id="email" value="<?= $email; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= $nama; ?>" required>
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor WhatsApp</label>
                <input type="text" id="no_hp" name="no_hp" value="<?= $no_hp; ?>" required>
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>

        <div class="info">
            <a href="dashboard.php">← Kembali ke Dashboard</a>
        </div>
    </div>

</body>
</html>