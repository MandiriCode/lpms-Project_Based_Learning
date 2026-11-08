<?php
require_role(['guru', 'admin']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = (int)($_POST['unggah_id'] ?? 0);
    $vals = [];
    foreach (['fluency', 'flexibility', 'originality', 'elaboration'] as $k) {
        $v = (float)($_POST[$k] ?? 0);
        if ($v < 0 || $v > 100) {
            flash('Nilai indikator harus 0–100.', 'error');
            redirect('index.php?page=assessment');
        }
        $vals[] = $v;
    }
    $check = $pdo->prepare("SELECT u.id FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id WHERE u.id=?" . (role() === 'guru' ? " AND p.guru_id=?" : ""));
    $args = [$sid];
    if (role() === 'guru') $args[] = (int)current_user()['id'];
    $check->execute($args);
    if ($check->fetch()) {
        $avg = array_sum($vals) / 4;
        $pdo->prepare("INSERT INTO penilaian(unggah_id,guru_id,fluency,flexibility,originality,elaboration,umpan_balik,nilai_akhir) VALUES(?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE guru_id=VALUES(guru_id),fluency=VALUES(fluency),flexibility=VALUES(flexibility),originality=VALUES(originality),elaboration=VALUES(elaboration),umpan_balik=VALUES(umpan_balik),nilai_akhir=VALUES(nilai_akhir)")->execute([$sid, (int)current_user()['id'], ...$vals, post('umpan_balik'), $avg]);
        flash('Penilaian tersimpan.');
    } else flash('Pengumpulan tidak ditemukan atau bukan proyek Anda.', 'error');
    redirect('index.php?page=assessment');
}
$sql = "SELECT u.id,p.judul,s.nama siswa FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id JOIN `user` s ON s.id=u.siswa_id";
if (role() === 'guru') $sql .= " WHERE p.guru_id=" . (int)current_user()['id'];
$subs = $pdo->query($sql)->fetchAll();
$rows = $pdo->query("SELECT n.*,s.nama siswa,p.judul FROM penilaian n JOIN unggah_proyek u ON u.id=n.unggah_id JOIN `user` s ON s.id=u.siswa_id JOIN proyek p ON p.id=u.proyek_id" . (role() === 'guru' ? " WHERE p.guru_id=" . (int)current_user()['id'] : "") . " ORDER BY n.created_at DESC")->fetchAll();
?><div class="page-heading">
    <div><span class="eyebrow">PENDAMPINGAN PEMBELAJARAN</span>
        <h1>Penilaian Kreativitas</h1>
        <p class="muted">Kelola perkembangan dan evaluasi siswa tanpa memenuhi area kerja.</p>
    </div>
</div>
<button type="button" class="primary-button" data-open-modal="assessment-modal">＋ Beri Penilaian</button>
<dialog class="app-modal" id="assessment-modal">
    <div class="modal-head">
        <div><span class="eyebrow">FORMULIR LPMS</span>
            <h2>Rubrik penilaian kreativitas</h2>
        </div><button type="button" class="modal-x" data-close-modal="">×</button>
    </div>
    <form class="modal-form" method="post">
        <h3>Rubrik penilaian proyek</h3><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><label>Pengumpulan</label><select name="unggah_id" required><?php foreach ($subs as $x): ?><option value="<?= $x['id'] ?>"><?= e($x['judul'] . ' — ' . $x['siswa']) ?></option><?php endforeach; ?></select>
        <p class="muted">Masukkan skor setiap indikator pada rentang 0 sampai 100.</p><?php foreach (['fluency' => 'Fluency (kelancaran)', 'flexibility' => 'Flexibility (keluwesan)', 'originality' => 'Originality (orisinalitas)', 'elaboration' => 'Elaboration (elaborasi)'] as $k => $v): ?><label><?= e($v) ?></label><input type="number" name="<?= e($k) ?>" min="0" max="100" step="0.01" value="0" required><?php endforeach; ?><label>Umpan balik</label><textarea name="umpan_balik"></textarea>
        <div class="modal-actions"><button type="button" class="btn-secondary" data-close-modal="">Batal</button><button>Simpan Penilaian</button></div>
    </form>
</dialog>
<div class="panel">
    <h3>Rekap penilaian</h3>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Siswa</th>
                <th>Proyek</th>
                <th>Fluency</th>
                <th>Flexibility</th>
                <th>Originality</th>
                <th>Elaboration</th>
                <th>Rata-rata</th>
                <th>Umpan balik</th>
            </tr><?php foreach ($rows as $x): ?><tr>
                    <td><?= e($x['siswa']) ?></td>
                    <td><?= e($x['judul']) ?></td>
                    <td><?= e($x['fluency']) ?></td>
                    <td><?= e($x['flexibility']) ?></td>
                    <td><?= e($x['originality']) ?></td>
                    <td><?= e($x['elaboration']) ?></td>
                    <td><b><?= e($x['nilai_akhir']) ?></b></td>
                    <td><?= e($x['umpan_balik']) ?></td>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div>