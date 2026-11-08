<?php
if (role() === 'siswa') {
    $rows = $pdo->query("SELECT p.*,u.nama guru FROM proyek p JOIN `user` u ON u.id=p.guru_id WHERE p.status='aktif' ORDER BY p.tenggat")->fetchAll();
} else {
    require_role(['guru', 'admin']);
    if (isset($_GET['delete'])) {
        $pdo->prepare("DELETE FROM proyek WHERE id=? AND (?='admin' OR guru_id=?)")->execute([(int)$_GET['delete'], role(), (int)current_user()['id']]);
        flash('Proyek dihapus.');
        redirect('index.php?page=projects');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $data = [post('judul'), post('deskripsi'), post('instruksi'), post('tanggal_mulai') ?: null, post('tenggat') ?: null, post('status')];
        if ($id) {
            $sql = "UPDATE proyek SET judul=?,deskripsi=?,instruksi=?,tanggal_mulai=?,tenggat=?,status=? WHERE id=?";
            $data[] = $id;
            if (role() === 'guru') {
                $sql .= " AND guru_id=?";
                $data[] = (int)current_user()['id'];
            }
            $pdo->prepare($sql)->execute($data);
        } else {
            $pdo->prepare("INSERT INTO proyek(guru_id,judul,deskripsi,instruksi,tanggal_mulai,tenggat,status) VALUES(?,?,?,?,?,?,?)")->execute([(int)current_user()['id'], ...$data]);
        }
        flash('Proyek tersimpan.');
        redirect('index.php?page=projects');
    }
    $edit = null;
    if (isset($_GET['edit'])) {
        $s = $pdo->prepare("SELECT * FROM proyek WHERE id=?" . (role() === 'guru' ? " AND guru_id=?" : ""));
        $args = [(int)$_GET['edit']];
        if (role() === 'guru') $args[] = (int)current_user()['id'];
        $s->execute($args);
        $edit = $s->fetch();
    }
    $sql = "SELECT p.*,u.nama guru FROM proyek p JOIN `user` u ON u.id=p.guru_id";
    if (role() === 'guru') $sql .= " WHERE p.guru_id=" . (int)current_user()['id'];
    $sql .= " ORDER BY p.created_at DESC";
    $rows = $pdo->query($sql)->fetchAll();
}
?>

<div class="page-heading">
    <div><span class="eyebrow">PEMBELAJARAN BERBASIS PROYEK</span>
        <h1><?= role() === 'siswa' ? 'Jelajahi Proyek' : 'Manajemen Proyek' ?></h1>
        <p class="muted"><?= role() === 'siswa' ? 'Temukan tugas dan instruksi dari guru.' : 'Rancang aktivitas, atur tenggat, dan pantau proyek kelas.' ?></p>
    </div>

</div>
<hr>
<?php if (role() !== 'siswa'): ?>
    <button type="button" class="primary-button" data-open-modal="project-modal">
        <?= $edit ? 'Ubah Proyek' : '＋ Buat Proyek' ?>
    </button>
<?php endif; ?>
<?php if (role() !== 'siswa'): ?><dialog class="app-modal" id="project-modal">
        <div class="modal-head">
            <div><span class="eyebrow">FORMULIR PROYEK</span>
                <h2><?= $edit ? 'Edit Proyek' : 'Buat Proyek Baru' ?></h2>
            </div><button type="button" class="modal-x" data-close-modal="">×</button>
        </div>
        <form class="modal-form" method="post"><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>"><label>Judul</label><input name="judul" value="<?= e($edit['judul'] ?? '') ?>" required><label>Deskripsi</label><textarea name="deskripsi" required><?= e($edit['deskripsi'] ?? '') ?></textarea><label>Instruksi</label><textarea name="instruksi"><?= e($edit['instruksi'] ?? '') ?></textarea><label>Tanggal mulai</label><input type="date" name="tanggal_mulai" value="<?= e($edit['tanggal_mulai'] ?? '') ?>"><label>Tenggat</label><input type="date" name="tenggat" value="<?= e($edit['tenggat'] ?? '') ?>"><label>Status</label><select name="status">
                <option value="aktif">Aktif</option>
                <option value="draft" <?= ($edit['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="selesai" <?= ($edit['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
            <div class="modal-actions"><button type="button" class="btn-secondary" data-close-modal="">Batal</button><button>Simpan Proyek</button></div>
        </form>
    </dialog><?php endif; ?>
<div class="panel">
    <div class="table-toolbar">
        <div><b><?= role() === 'siswa' ? 'Proyek tersedia' : 'Daftar proyek' ?></b><small><?= count($rows) ?> proyek</small></div>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Proyek</th>
                <th>Guru</th>
                <th>Tenggat</th>
                <th>Status</th><?php if (role() !== 'siswa'): ?><th>Aksi</th><?php else: ?><th>Instruksi</th><?php endif; ?>
            </tr><?php foreach ($rows as $x): ?><tr>
                    <td><?= e($x['judul']) ?><br><small><?= e($x['deskripsi']) ?></small></td>
                    <td><?= e($x['guru']) ?></td>
                    <td><?= e($x['tenggat']) ?></td>
                    <td><?= e($x['status']) ?></td>
                    <td><?php if (role() === 'siswa'): ?>
                            <a href="index.php?page=submissions&project=<?= $x['id'] ?>">Kumpulkan PDF</a>
                        <?php else: ?>
                            <a href="index.php?page=projects&edit=<?= $x['id'] ?>" class="btn btn-secondary">Edit</a>
                            <a onclick="return confirm('Hapus proyek?')" href="index.php?page=projects&delete=<?= $x['id'] ?>" class="btn btn-danger">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (new URLSearchParams(location.search).has('edit')) document.getElementById('project-modal')?.showModal();
    });
</script>