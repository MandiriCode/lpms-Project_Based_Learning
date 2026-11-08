<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = post('nama');
    $pass = (string)($_POST['password'] ?? '');
    if ($nama === '') $error = 'Nama tidak boleh kosong.';
    elseif ($pass !== '' && strlen($pass) < 10) $error = 'Password minimal 10 karakter.';
    else {
        if ($pass !== '') $pdo->prepare("UPDATE `user` SET nama=?,password=? WHERE id=?")->execute([$nama, password_hash($pass, PASSWORD_DEFAULT), (int)current_user()['id']]);
        else $pdo->prepare("UPDATE `user` SET nama=? WHERE id=?")->execute([$nama, (int)current_user()['id']]);
        $_SESSION['user']['nama'] = $nama;
        flash('Profil diperbarui.');
        redirect('index.php?page=profile');
    }
}
$s = $pdo->prepare("SELECT * FROM `user` WHERE id=?");
$s->execute([(int)current_user()['id']]);
$u = $s->fetch();
?><h1>Profil Saya</h1>
<form class="panel narrow" method="post"><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><label>Nama</label><input name="nama" value="<?= e($u['nama']) ?>" required><label>Username</label><input value="<?= e($u['username']) ?>" disabled><label>Role</label><input value="<?= e($u['role']) ?>" disabled><label>Password baru (kosongkan jika tidak diubah)</label><input type="password" name="password" minlength="10"><button>Perbarui Profil</button></form>