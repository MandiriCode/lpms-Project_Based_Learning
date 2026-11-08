<?php
require_role(['admin']);
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== (int)current_user()['id']) {
        $pdo->prepare("DELETE FROM `user` WHERE id=?")->execute([$id]);
        flash('Pengguna dihapus.');
    }
    redirect('index.php?page=users');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = post('nama');
    $username = post('username');
    $roleIn = post('role');
    $pass = (string)($_POST['password'] ?? '');
    if ($nama === '' || $username === '' || !in_array($roleIn, ['admin', 'guru', 'siswa'], true)) $error = 'Data belum lengkap.';
    else {
        try {
            if ($id) {
                if ($pass !== '') $pdo->prepare("UPDATE `user` SET nama=?,username=?,role=?,nis_nip=?,kelas=?,jurusan=?,password=? WHERE id=?")->execute([$nama, $username, $roleIn, post('nis_nip'), post('kelas'), post('jurusan'), password_hash($pass, PASSWORD_DEFAULT), $id]);
                else $pdo->prepare("UPDATE `user` SET nama=?,username=?,role=?,nis_nip=?,kelas=?,jurusan=? WHERE id=?")->execute([$nama, $username, $roleIn, post('nis_nip'), post('kelas'), post('jurusan'), $id]);
            } else {
                if (strlen($pass) < 10) throw new Exception('Password minimal 10 karakter.');
                $pdo->prepare("INSERT INTO `user`(nama,username,role,nis_nip,kelas,jurusan,password) VALUES(?,?,?,?,?,?,?)")->execute([$nama, $username, $roleIn, post('nis_nip'), post('kelas'), post('jurusan'), password_hash($pass, PASSWORD_DEFAULT)]);
            }
            flash('Data pengguna tersimpan.');
            redirect('index.php?page=users');
        } catch (Throwable $e) {
            $error = $e instanceof PDOException ? 'Username sudah digunakan.' : $e->getMessage();
        }
    }
}
$edit = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare("SELECT * FROM `user` WHERE id=?");
    $s->execute([(int)$_GET['edit']]);
    $edit = $s->fetch();
}
$rows = $pdo->query("SELECT * FROM `user` ORDER BY role,nama")->fetchAll();
?><div class="page-heading">
    <div><span class="eyebrow">ADMINISTRASI SEKOLAH</span>
        <h1>Manajemen Pengguna</h1>
        <p class="muted">Kelola akun guru, siswa, dan administrator dari satu tempat.</p>
    </div>
</div>
<button type="button" class="primary-button" data-open-modal="user-modal"><?= $edit ? 'Ubah Pengguna' : '＋ Tambah Pengguna' ?></button>

<div class="panel">
    <div class="table-toolbar">
        <div><b>Daftar pengguna</b><small><?= count($rows) ?> akun terdaftar</small></div><span class="toolbar-note">Admin · Guru · Siswa</span>
    </div>
    <dialog class="app-modal" id="user-modal">
        <div class="modal-head">
            <div><span class="eyebrow">FORMULIR AKUN</span>
                <h2><?= $edit ? 'Ubah Pengguna' : 'Tambah Pengguna' ?></h2>
            </div><button type="button" class="modal-x" data-close-modal="">×</button>
        </div>
        <form class="modal-form" method="post">
            <h3><?= $edit ? 'Ubah' : 'Tambah' ?> Pengguna</h3><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>"><?php foreach (['nama' => 'Nama lengkap', 'username' => 'Username', 'nis_nip' => 'NIS/NIP', 'kelas' => 'Kelas (contoh X GP)', 'jurusan' => 'Jurusan'] as $k => $label): ?><label><?= e($label) ?></label><input name="<?= e($k) ?>" value="<?= e($edit[$k] ?? '') ?> " <?= in_array($k, ['nama', 'username']) ? 'required' : '' ?>><?php endforeach; ?><label>Role</label><select name="role">
                <option value="siswa" <?= ($edit['role'] ?? '') === 'siswa' ? 'selected' : '' ?>>Siswa</option>
                <option value="guru" <?= ($edit['role'] ?? '') === 'guru' ? 'selected' : '' ?>>Guru</option>
                <option value="admin" <?= ($edit['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select><label>Password <?= $edit ? '(kosongkan jika tidak diubah)' : '(min. 10 karakter)' ?></label><input type="password" name="password" <?= $edit ? '' : 'required minlength="10"' ?>>
            <div class="modal-actions"><button type="button" class="btn-secondary" data-close-modal="">Batal</button><button>Simpan Pengguna</button></div>
        </form>
    </dialog>
    <div class="table-wrap">
        <table>
            <tr>
                <th style="width: 20%;">Nama</th>
                <th style="width: 20%;">Username</th>
                <th style="width: 20%;">Role</th>
                <th style="width: 20%;">Kelas</th>
                <th>Aksi</th>
            </tr><?php foreach ($rows as $x): ?><tr>
                    <td><?= e($x['nama']) ?></td>
                    <td><?= e($x['username']) ?></td>
                    <td><?= e($x['role']) ?></td>
                    <td><?= e($x['kelas']) ?></td>
                    <td><a href="index.php?page=users&edit=<?= $x['id'] ?>" class="btn btn-secondary">Edit</a> · <a onclick="return confirm('Hapus pengguna ini?')" href="index.php?page=users&delete=<?= $x['id'] ?>" class="btn btn-danger">Hapus</a></td>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (new URLSearchParams(location.search).has('edit') || document.querySelector('.alert.error')) document.getElementById('user-modal')?.showModal();
    });
</script>