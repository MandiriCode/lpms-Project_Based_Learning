<?php
if (role() === 'guru' || role() === 'admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sid = (int)($_POST['siswa_id'] ?? 0);
        $jenis = post('jenis');
        $vals = [];
        foreach (['fluency', 'flexibility', 'originality', 'elaboration'] as $k) {
            $v = (float)($_POST[$k] ?? 0);
            if ($v < 0 || $v > 100) {
                flash('Nilai harus 0–100.', 'error');
                redirect('index.php?page=tests');
            }
            $vals[] = $v;
        }
        $check = $pdo->prepare("SELECT id FROM `user` WHERE id=? AND role='siswa'");
        $check->execute([$sid]);
        if ($check->fetch() && in_array($jenis, ['pretest', 'posttest'], true)) {
            $pdo->prepare("INSERT INTO hasil_tes(siswa_id,jenis,fluency,flexibility,originality,elaboration,catatan,guru_id) VALUES(?,?,?,?,?,?,?,?)")->execute([$sid, $jenis, ...$vals, post('catatan'), (int)current_user()['id']]);
            flash('Hasil tes tersimpan.');
        } else flash('Siswa atau jenis tes tidak valid.', 'error');
        redirect('index.php?page=tests');
    }
    $students = $pdo->query("SELECT id,nama,kelas FROM `user` WHERE role='siswa' ORDER BY nama")->fetchAll();
    $rows = $pdo->query("SELECT h.*,s.nama siswa,g.nama guru FROM hasil_tes h JOIN `user` s ON s.id=h.siswa_id JOIN `user` g ON g.id=h.guru_id ORDER BY h.created_at DESC")->fetchAll();
} else {
    $s = $pdo->prepare("SELECT * FROM hasil_tes WHERE siswa_id=? ORDER BY created_at DESC");
    $s->execute([(int)current_user()['id']]);
    $rows = $s->fetchAll();
}
?><div class="page-heading">
    <div><span class="eyebrow">PENDAMPINGAN PEMBELAJARAN</span>
        <h1>Pretest dan Posttest</h1>
        <p class="muted">Kelola perkembangan dan evaluasi siswa tanpa memenuhi area kerja.</p>
    </div>
</div>
<?php if (in_array(role(), ['guru', 'admin'], true)): ?><button type="button" class="primary-button" data-open-modal="tests-modal">＋ Input Hasil Tes</button><?php endif; ?><?php if (in_array(role(), ['guru', 'admin'], true)): ?><dialog class="app-modal" id="tests-modal">
        <div class="modal-head">
            <div><span class="eyebrow">FORMULIR LPMS</span>
                <h2>Input hasil pretest atau posttest</h2>
            </div><button type="button" class="modal-x" data-close-modal="">×</button>
        </div>
        <form class="modal-form" method="post">
            <h3>Input hasil tes uraian</h3><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><label>Siswa</label><select name="siswa_id" required><?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['nama'] . ' — ' . $s['kelas']) ?></option><?php endforeach; ?></select><label>Jenis tes</label><select name="jenis">
                <option value="pretest">Pretest</option>
                <option value="posttest">Posttest</option>
            </select><?php foreach (['fluency' => 'Fluency', 'flexibility' => 'Flexibility', 'originality' => 'Originality', 'elaboration' => 'Elaboration'] as $k => $v): ?><label><?= e($v) ?> (0–100)</label><input type="number" name="<?= e($k) ?>" min="0" max="100" step="0.01" value="0" required><?php endforeach; ?><label>Catatan penilaian</label><textarea name="catatan"></textarea>
            <div class="modal-actions"><button type="button" class="btn-secondary" data-close-modal="">Batal</button><button>Simpan Hasil Tes</button></div>
        </form>
    </dialog><?php endif; ?><div class="panel">
    <h3>Riwayat hasil tes</h3>
    <div class="table-wrap">
        <table>
            <tr><?php if (role() !== 'siswa'): ?><th>Siswa</th><?php endif; ?><th>Jenis</th>
                <th>Fluency</th>
                <th>Flexibility</th>
                <th>Originality</th>
                <th>Elaboration</th>
                <th>Rata-rata</th>
                <th>Tanggal</th>
            </tr><?php foreach ($rows as $x): $avg = ((float)$x['fluency'] + (float)$x['flexibility'] + (float)$x['originality'] + (float)$x['elaboration']) / 4; ?><tr><?php if (role() !== 'siswa'): ?><td><?= e($x['siswa']) ?></td><?php endif; ?><td><?= e($x['jenis']) ?></td>
                    <td><?= e($x['fluency']) ?></td>
                    <td><?= e($x['flexibility']) ?></td>
                    <td><?= e($x['originality']) ?></td>
                    <td><?= e($x['elaboration']) ?></td>
                    <td><?= number_format($avg, 2) ?></td>
                    <td><?= e($x['created_at']) ?></td>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div>