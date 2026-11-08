<?php
require_role(['guru', 'admin']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = (int)($_POST['unggah_id'] ?? 0);
    $progress = max(0, min(100, (int)($_POST['progres'] ?? 0)));
    $check = $pdo->prepare("SELECT u.id FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id WHERE u.id=?" . (role() === 'guru' ? " AND p.guru_id=?" : ""));
    $args = [$sid];
    if (role() === 'guru') $args[] = (int)current_user()['id'];
    $check->execute($args);
    if ($check->fetch()) {
        $pdo->prepare("INSERT INTO monitoring(unggah_id,guru_id,catatan,progres) VALUES(?,?,?,?)")->execute([$sid, (int)current_user()['id'], post('catatan'), $progress]);
        flash('Catatan monitoring disimpan.');
    } else flash('Data tidak ditemukan atau bukan proyek Anda.', 'error');
    redirect('index.php?page=monitoring');
}
$sql = "SELECT u.id,u.file_asli,p.judul,s.nama siswa FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id JOIN `user` s ON s.id=u.siswa_id";
if (role() === 'guru') $sql .= " WHERE p.guru_id=" . (int)current_user()['id'];
$sql .= " ORDER BY p.judul,s.nama";
$subs = $pdo->query($sql)->fetchAll();
$hist = $pdo->query("SELECT m.*,u.file_asli,p.judul,s.nama siswa,g.nama guru FROM monitoring m JOIN unggah_proyek u ON u.id=m.unggah_id JOIN proyek p ON p.id=u.proyek_id JOIN `user` s ON s.id=u.siswa_id JOIN `user` g ON g.id=m.guru_id" . (role() === 'guru' ? " WHERE p.guru_id=" . (int)current_user()['id'] : "") . " ORDER BY m.created_at DESC")->fetchAll();
?><div class="page-heading">
    <div><span class="eyebrow">PENDAMPINGAN PEMBELAJARAN</span>
        <h1>Monitoring Progres</h1>
        <p class="muted">Kelola perkembangan dan evaluasi siswa tanpa memenuhi area kerja.</p>
    </div>
</div>
<button type="button" class="primary-button" data-open-modal="monitoring-modal">＋ Catat Progres</button>
<dialog class="app-modal" id="monitoring-modal">
    <div class="modal-head">
        <div><span class="eyebrow">FORMULIR LPMS</span>
            <h2>Catatan perkembangan siswa</h2>
        </div><button type="button" class="modal-x" data-close-modal="">×</button>
    </div>
    <form class="modal-form" method="post">
        <h3>Tambah catatan perkembangan</h3><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><label>Pengumpulan siswa</label><select name="unggah_id" required><?php foreach ($subs as $x): ?><option value="<?= $x['id'] ?>"><?= e($x['judul'] . ' — ' . $x['siswa']) ?></option><?php endforeach; ?></select><label>Progres (%)</label><input type="number" name="progres" min="0" max="100" value="0" required><label>Catatan guru</label><textarea name="catatan" required></textarea>
        <div class="modal-actions"><button type="button" class="btn-secondary" data-close-modal="">Batal</button><button>Simpan Monitoring</button></div>
    </form>
</dialog>
<div class="panel">
    <h3>Riwayat monitoring</h3>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Siswa</th>
                <th>Proyek</th>
                <th>Progres</th>
                <th>Catatan</th>
                <th>Waktu</th>
            </tr><?php foreach ($hist as $x): ?><tr>
                    <td><?= e($x['siswa']) ?></td>
                    <td><?= e($x['judul']) ?></td>
                    <td><?= e($x['progres']) ?>%</td>
                    <td><?= e($x['catatan']) ?></td>
                    <td><?= e($x['created_at']) ?></td>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div>