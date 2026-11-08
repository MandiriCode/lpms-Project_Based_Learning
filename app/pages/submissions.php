<?php
if (role() === 'siswa') {
    $uid = (int)current_user()['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pid = (int)($_POST['proyek_id'] ?? 0);
        $s = $pdo->prepare("SELECT id FROM proyek WHERE id=? AND status='aktif'");
        $s->execute([$pid]);
        if (!$s->fetch()) {
            flash('Proyek tidak tersedia.', 'error');
            redirect('index.php?page=submissions');
        }
        if (empty($_FILES['pdf']['name']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
            flash('Pilih file PDF yang valid.', 'error');
            redirect('index.php?page=submissions&project=' . $pid);
        }
        $f = $_FILES['pdf'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        if ($ext !== 'pdf' || $mime !== 'application/pdf' || $f['size'] > MAX_UPLOAD_SIZE) {
            flash('File harus PDF dan maksimal 10 MB.', 'error');
            redirect('index.php?page=submissions&project=' . $pid);
        }
        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
        $name = bin2hex(random_bytes(16)) . '.pdf';
        if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . $name)) {
            flash('Gagal menyimpan file.', 'error');
            redirect('index.php?page=submissions&project=' . $pid);
        }
        $old = $pdo->prepare("SELECT nama_file FROM unggah_proyek WHERE proyek_id=? AND siswa_id=?");
        $old->execute([$pid, $uid]);
        $oldName = $old->fetchColumn();
        $sql = "INSERT INTO unggah_proyek(proyek_id,siswa_id,nama_file,file_asli,catatan,status) VALUES(?,?,?,?,?,'terkirim') ON DUPLICATE KEY UPDATE nama_file=VALUES(nama_file),file_asli=VALUES(file_asli),catatan=VALUES(catatan),status='terkirim',uploaded_at=CURRENT_TIMESTAMP";
        $pdo->prepare($sql)->execute([$pid, $uid, $name, basename($f['name']), post('catatan')]);
        if ($oldName && is_file(UPLOAD_DIR . $oldName)) @unlink(UPLOAD_DIR . $oldName);
        flash('PDF berhasil dikumpulkan.');
        redirect('index.php?page=submissions');
    }
    $projects = $pdo->query("SELECT id,judul FROM proyek WHERE status='aktif' ORDER BY judul")->fetchAll();
    $s = $pdo->prepare("SELECT u.*,p.judul FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id WHERE u.siswa_id=? ORDER BY u.uploaded_at DESC");
    $s->execute([$uid]);
    $rows = $s->fetchAll();
} else {
    require_role(['guru', 'admin']);
    $sql = "SELECT u.*,p.judul,s.nama siswa,s.kelas FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id JOIN `user` s ON s.id=u.siswa_id";
    if (role() === 'guru') $sql .= " WHERE p.guru_id=" . (int)current_user()['id'];
    $sql .= " ORDER BY u.uploaded_at DESC";
    $rows = $pdo->query($sql)->fetchAll();
}
?><div class="page-heading">
    <div><span class="eyebrow">DOKUMENTASI KARYA</span>
        <h1><?= role() === 'siswa' ? 'Pengumpulan Saya' : 'Pengumpulan Tugas Siswa' ?></h1>
        <p class="muted">Pantau berkas proyek dan status pengumpulan siswa.</p>
    </div><?php if (role() === 'siswa'): ?><button type="button" class="primary-button" data-open-modal="upload-modal">＋ Unggah Karya</button><?php endif; ?>
</div>
<?php if (role() === 'siswa'): ?><dialog class="app-modal" id="upload-modal">
        <div class="modal-head">
            <div><span class="eyebrow">PENGUMPULAN PROYEK</span>
                <h2>Unggah hasil proyek</h2>
            </div><button type="button" class="modal-x" data-close-modal="">x</button>
        </div>
        <form class="modal-form" method="post" enctype="multipart/form-data"><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><label>Pilih proyek</label><select name="proyek_id" required><?php foreach ($projects as $p): ?><option value="<?= $p['id'] ?>" <?= ((int)($_GET['project'] ?? 0) === (int)$p['id']) ? 'selected' : '' ?>><?= e($p['judul']) ?></option><?php endforeach; ?></select><label>File PDF (maksimal 10 MB)</label><input type="file" name="pdf" accept="application/pdf,.pdf" required><label>Catatan</label><textarea name="catatan"></textarea>
            <div class="modal-actions"><button type="button" class="btn-secondary" data-close-modal="">Batal</button><button>Unggah PDF</button></div>
        </form>
    </dialog><?php endif; ?>
<div class="panel">
    <div class="table-wrap">
        <table>
            <tr><?php if (role() !== 'siswa'): ?><th>Siswa</th><?php endif; ?><th>Proyek</th>
                <th>File</th>
                <th>Status</th>
                <th>Tanggal</th><?php if (role() !== 'siswa'): ?><th>Aksi</th><?php endif; ?>
            </tr><?php foreach ($rows as $x): ?><tr><?php if (role() !== 'siswa'): ?><td><?= e($x['siswa']) ?> (<?= e($x['kelas']) ?>)</td><?php endif; ?><td><?= e($x['judul']) ?></td>
                    <td><a href="download.php?id=<?= $x['id'] ?>"><?= e($x['file_asli']) ?></a></td>
                    <td><?= e($x['status']) ?></td>
                    <td><?= e($x['uploaded_at']) ?></td>
                    <?php if (role() !== 'siswa'): ?>
                        <td><a href="index.php?page=monitoring&submission=<?= $x['id'] ?>" ">Monitoring</a>
                            <a href=" index.php?page=assessment&submission=<?= $x['id'] ?>">Nilai</a>
                        </td><?php endif; ?>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div>