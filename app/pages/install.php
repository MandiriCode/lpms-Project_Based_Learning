<?php
$count = (int)$pdo->query("SELECT COUNT(*) FROM `user` WHERE role='admin'")->fetchColumn();
$error = '';
if ($count > 0) { ?>
    <!doctype html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/style.css?v=final1">
        <title>Instalasi LPMS</title>
    </head>

    <body class="public-body">
        <div class="install-card"><span class="brand-mark">L</span>
            <h1>Instalasi selesai</h1>
            <p>Akun administrator sudah tersedia.</p><a class="primary-button" href="index.php?page=login&role=admin">Login Administrator →</a>
        </div>
    </body>

    </html><?php exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = post('nama');
            $username = post('username');
            $pass = (string)($_POST['password'] ?? '');
            if ($nama === '' || $username === '' || strlen($pass) < 10) $error = 'Lengkapi data. Password minimal 10 karakter.';
            else {
                try {
                    $q = $pdo->prepare("INSERT INTO `user`(nama,username,password,role) VALUES(?,?,?,'admin')");
                    $q->execute([$nama, $username, password_hash($pass, PASSWORD_DEFAULT)]);
                    redirect('index.php?page=login&role=admin');
                } catch (PDOException $e) {
                    $error = 'Username sudah digunakan.';
                }
            }
        } ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/style.css?v=final1">
    <title>Instalasi LPMS</title>
</head>

<body class="public-body">
    <div class="install-wrap">
        <form class="install-card" method="post"><span class="brand-mark">L</span><span class="eyebrow">PENGATURAN AWAL</span>
            <h1>Buat administrator</h1>
            <p>Akun ini digunakan untuk mengelola pengguna dan aktivitas sistem.</p><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><label>Nama lengkap</label><input name="nama" required><label>Username</label><input name="username" required><label>Password (minimal 10 karakter)</label><input type="password" name="password" minlength="10" required><button class="primary-button">Buat akun admin →</button>
        </form>
    </div>
</body>

</html>