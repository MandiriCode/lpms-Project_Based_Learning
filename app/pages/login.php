<?php
$error = '';
$requestedRole = (string)($_GET['role'] ?? '');
if (!in_array($requestedRole, ['guru', 'siswa', 'admin'], true)) $requestedRole = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q = $pdo->prepare("SELECT * FROM `user` WHERE username=? LIMIT 1");
    $q->execute([post('username')]);
    $row = $q->fetch();
    $selected = post('role');
    if ($row && password_verify((string)($_POST['password'] ?? ''), $row['password']) && ($selected === '' || $row['role'] === $selected)) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user'] = ['id' => $row['id'], 'nama' => $row['nama'], 'username' => $row['username'], 'role' => $row['role']];
        redirect('index.php');
    }
    $error = 'Username, password, atau jenis akun tidak sesuai.';
}
$labels = ['guru' => 'Guru', 'siswa' => 'Siswa', 'admin' => 'Administrator'];
$title = $labels[$requestedRole] ?? 'Pengguna'; ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login <?= $title ?> · LPMS</title>
    <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/style.css?v=final1">
</head>

<body class="public-body"><a class="public-back" href="index.php">← Kembali ke beranda</a>
    <div class="auth-screen">
        <section class="auth-art"><a class="auth-logo" href="index.php"><span class="brand-mark">L</span> LPMS</a>
            <div class="auth-art-copy"><span class="eyebrow light">LEARNING PROJECT MONITORING SYSTEM</span>
                <h1>Setiap ide punya<br><em>ruang untuk tumbuh.</em></h1>
                <p>Kolaborasi, proses berkarya, dan kreativitas dalam satu perjalanan pembelajaran.</p>
            </div>
            <div class="auth-art-foot">PROJECT BASED LEARNING · CREATIVE THINKING</div><span class="auth-star star-one">✦</span><span class="auth-star star-two">✳</span>
        </section>
        <section class="auth-form-side">
            <form class="auth-form" method="post">
                <div class="auth-form-head"><span class="eyebrow">SELAMAT DATANG KEMBALI</span>
                    <h2>Login <?= $title ?></h2>
                    <p>Masukkan akun Anda untuk melanjutkan ke ruang kerja LPMS.</p>
                </div><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><?php if ($requestedRole): ?><input type="hidden" name="role" value="<?= e($requestedRole) ?>">
                    <div class="selected-role"><b>Masuk sebagai <?= $title ?></b><a href="index.php?page=login">Ganti</a></div><?php else: ?><label>Masuk sebagai</label><select name="role" required>
                        <option value="">Pilih jenis akun</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                        <option value="admin">Administrator</option>
                    </select><?php endif; ?><label>Username</label><input name="username" placeholder="Masukkan username" required autofocus><label>Password</label><input type="password" name="password" placeholder="Masukkan password" required><button class="primary-button">Masuk ke LPMS <span>→</span></button>
                <p class="auth-help">Belum memiliki akun? Hubungi administrator sekolah.</p>
            </form>
        </section>
    </div>
</body>

</html>