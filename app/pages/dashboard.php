<?php
$uid = (int)current_user()['id'];
$r = role();
$stats = [];
$quick = [];
if ($r === 'admin') {
    $stats = ['Total Pengguna' => (int)$pdo->query("SELECT COUNT(*) FROM `user`")->fetchColumn(), 'Guru' => (int)$pdo->query("SELECT COUNT(*) FROM `user` WHERE role='guru'")->fetchColumn(), 'Siswa' => (int)$pdo->query("SELECT COUNT(*) FROM `user` WHERE role='siswa'")->fetchColumn(), 'Proyek Aktif' => (int)$pdo->query("SELECT COUNT(*) FROM proyek WHERE status='aktif'")->fetchColumn()];
    $quick = [['users', 'Manajemen Pengguna', 'Kelola akun guru, siswa, dan administrator'], ['projects', 'Daftar Proyek', 'Pantau proyek pembelajaran'], ['tests', 'Rekap Tes', 'Lihat hasil pretest dan posttest']];
    $heading = 'Pusat kendali sistem';
    $desc = 'Kelola akun dan pantau aktivitas pembelajaran dalam satu tempat.';
} elseif ($r === 'guru') {
    $q = $pdo->prepare("SELECT COUNT(*) FROM proyek WHERE guru_id=?");
    $q->execute([$uid]);
    $stats['Proyek Saya'] = (int)$q->fetchColumn();
    $q = $pdo->prepare("SELECT COUNT(*) FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id WHERE p.guru_id=?");
    $q->execute([$uid]);
    $stats['Pengumpulan Siswa'] = (int)$q->fetchColumn();
    $q = $pdo->prepare("SELECT COUNT(*) FROM penilaian n JOIN unggah_proyek u ON u.id=n.unggah_id JOIN proyek p ON p.id=u.proyek_id WHERE p.guru_id=?");
    $q->execute([$uid]);
    $stats['Sudah Dinilai'] = (int)$q->fetchColumn();
    $quick = [['projects', 'Buat Proyek', 'Susun aktivitas pembelajaran berbasis proyek'], ['submissions', 'Periksa Pengumpulan', 'Lihat berkas PDF hasil karya siswa'], ['monitoring', 'Catat Progres', 'Berikan catatan perkembangan siswa'], ['assessment', 'Nilai Kreativitas', 'Nilai empat indikator berpikir kreatif'], ['tests', 'Input Pretest/Posttest', 'Catat hasil tes uraian siswa']];
    $heading = 'Ruang kerja Guru';
    $desc = 'Rancang proyek, dampingi proses, dan evaluasi perkembangan kreativitas siswa.';
} else {
    $q = $pdo->prepare("SELECT COUNT(*) FROM unggah_proyek WHERE siswa_id=?");
    $q->execute([$uid]);
    $stats['Proyek Dikumpulkan'] = (int)$q->fetchColumn();
    $stats['Proyek Aktif'] = (int)$pdo->query("SELECT COUNT(*) FROM proyek WHERE status='aktif'")->fetchColumn();
    $q = $pdo->prepare("SELECT COUNT(*) FROM hasil_tes WHERE siswa_id=?");
    $q->execute([$uid]);
    $stats['Hasil Tes'] = (int)$q->fetchColumn();
    $quick = [['projects', 'Jelajahi Proyek', 'Lihat instruksi dan tenggat proyek'], ['submissions', 'Kumpulkan Karya', 'Unggah hasil proyek dalam format PDF'], ['tests', 'Lihat Hasil Tes', 'Pantau hasil pretest dan posttest'], ['profile', 'Profil Saya', 'Perbarui informasi akun']];
    $heading = 'Ruang belajar Siswa';
    $desc = 'Temukan proyek, kembangkan gagasan, dan dokumentasikan hasil karyamu.';
}
?>
<div class="welcome-banner"><span class="eyebrow light">DASHBOARD <?= e(strtoupper($r)) ?></span>
    <h1><?= e($heading) ?></h1>
    <p><?= e($desc) ?></p>
    <div class="welcome-decoration">✦　✳</div><img class="school-hero-art" src="assets/img/school-learning.svg" alt="Ilustrasi kegiatan belajar dan proyek siswa">
</div>
<div class="dashboard-greeting">
    <div>
        <h2>Halo, <?= e(current_user()['nama']) ?>!</h2>
        <p>Berikut ringkasan aktivitas LPMS Anda.</p>
    </div><span class="date-chip"><?= e(date('d-m-Y')) ?></span>
</div>
<div class="metric-grid"><?php foreach ($stats as $k => $v): ?><article class="metric-card"><span><?= e($k) ?></span><strong><?= e($v) ?></strong><small>Data saat ini</small></article><?php endforeach; ?></div>
<div class="section-heading">
    <h2>Akses cepat</h2>
    <p>Pilih aktivitas yang ingin Anda kerjakan.</p>
</div>
<div class="quick-grid"><?php foreach ($quick as [$url, $title, $desc]): ?><a class="quick-card" href="index.php?page=<?= $url ?>"><span class="quick-arrow">↗</span><b><?= e($title) ?></b>
            <p><?= e($desc) ?></p><span class="quick-link">Buka halaman →</span>
        </a><?php endforeach; ?></div>