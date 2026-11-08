<?php
$uid = (int)current_user()['id'];
$r = role();
$from = trim((string)($_GET['from'] ?? ''));
$to = trim((string)($_GET['to'] ?? ''));
$kelas = trim((string)($_GET['kelas'] ?? ''));
$export = (string)($_GET['export'] ?? '');
$where = [];
$params = [];
if ($from !== '') {
  $where[] = 'DATE(u.uploaded_at)>=?';
  $params[] = $from;
}
if ($to !== '') {
  $where[] = 'DATE(u.uploaded_at)<=?';
  $params[] = $to;
}
if ($kelas !== '' && $r !== 'siswa') {
  $where[] = 's.kelas=?';
  $params[] = $kelas;
}
if ($r === 'siswa') {
  $where[] = 'u.siswa_id=?';
  $params[] = $uid;
}
if ($r === 'guru') {
  $where[] = 'p.guru_id=?';
  $params[] = $uid;
}
$sql = "SELECT u.id AS unggah_id,u.uploaded_at,u.status AS status_pengumpulan,u.file_asli,
 p.judul,p.tenggat,s.nama AS siswa,s.kelas,
 n.fluency,n.flexibility,n.originality,n.elaboration,n.nilai_akhir,n.umpan_balik
 FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id
 JOIN `user` s ON s.id=u.siswa_id
 LEFT JOIN penilaian n ON n.unggah_id=u.id";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY u.uploaded_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

if ($export === 'csv') {
  $filename = 'laporan_lpms_' . date('Ymd_His') . '.csv';
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Cache-Control: no-store, no-cache, must-revalidate');
  $out = fopen('php://output', 'w');
  fwrite($out, "\xEF\xBB\xBF");
  fputcsv($out, ['Tanggal Pengumpulan', 'Nama Siswa', 'Kelas', 'Judul Proyek', 'Status', 'Fluency', 'Flexibility', 'Originality', 'Elaboration', 'Nilai Akhir', 'Umpan Balik']);
  foreach ($rows as $x) {
    fputcsv($out, [$x['uploaded_at'], $x['siswa'], $x['kelas'], $x['judul'], $x['status_pengumpulan'], $x['fluency'], $x['flexibility'], $x['originality'], $x['elaboration'], $x['nilai_akhir'], $x['umpan_balik']]);
  }
  fclose($out);
  exit;
}
if ($export === 'excel') {
  // SpreadsheetML 2003: workbook Excel yang dapat langsung dibuka tanpa library tambahan.
  $filename = 'laporan_lpms_' . date('Ymd_His') . '.xls';
  header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Cache-Control: no-store, no-cache, must-revalidate');
  $columns = ['Tanggal Pengumpulan', 'Nama Siswa', 'Kelas', 'Judul Proyek', 'Status', 'Fluency', 'Flexibility', 'Originality', 'Elaboration', 'Nilai Akhir', 'Umpan Balik'];
  $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
  $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
  $xml .= '<Worksheet ss:Name="Laporan LPMS"><Table>';
  $xml .= '<Row>';
  foreach ($columns as $col) {
    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($col, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</Data></Cell>';
  }
  $xml .= '</Row>';
  foreach ($rows as $x) {
    $values = [$x['uploaded_at'], $x['siswa'], $x['kelas'], $x['judul'], $x['status_pengumpulan'], $x['fluency'], $x['flexibility'], $x['originality'], $x['elaboration'], $x['nilai_akhir'], $x['umpan_balik']];
    $xml .= '<Row>';
    foreach ($values as $i => $value) {
      $numeric = in_array($i, [5, 6, 7, 8, 9], true) && $value !== null && $value !== '' && is_numeric($value);
      $type = $numeric ? 'Number' : 'String';
      $cell = $value === null ? '' : (string)$value;
      $xml .= '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars($cell, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</Data></Cell>';
    }
    $xml .= '</Row>';
  }
  $xml .= '</Table></Worksheet></Workbook>';
  echo "\xEF\xBB\xBF" . $xml;
  exit;
}

$classRows = [];
if ($r !== 'siswa') {
  $classRows = $pdo->query("SELECT DISTINCT kelas FROM `user` WHERE role='siswa' AND kelas IS NOT NULL AND kelas<>'' ORDER BY kelas")->fetchAll(PDO::FETCH_COLUMN);
}
$avgValues = array_filter(array_map(fn($x) => $x['nilai_akhir'] === null ? null : (float)$x['nilai_akhir'], $rows), fn($v) => $v !== null);
$average = count($avgValues) ? array_sum($avgValues) / count($avgValues) : null;
$queryBase = ['page' => 'reports'];
if ($from !== '') $queryBase['from'] = $from;
if ($to !== '') $queryBase['to'] = $to;
if ($kelas !== '') $queryBase['kelas'] = $kelas;
$csvUrl = 'index.php?' . http_build_query($queryBase + ['export' => 'csv']);
$excelUrl = 'index.php?' . http_build_query($queryBase + ['export' => 'excel']);
?>
<div class="report-heading">
  <div><span class="eyebrow">RINGKASAN PEMBELAJARAN</span>
    <h1>Laporan &amp; Rekapitulasi</h1>
    <p class="muted">Saring data berdasarkan periode dan kelas, lalu lihat atau unduh hasil laporan.</p>
  </div>
  <div class="report-downloads"><a class="primary-button report-download" href="<?= e($csvUrl) ?>">Unduh CSV <span>↓</span></a>
    <a class="primary-button report-download" href="<?= e($excelUrl) ?>">Unduh Excel (.xls) <span>↓</span></a>
  </div>
</div>
<form class="report-filter panel" method="get">
  <input type="hidden" name="page" value="reports">
  <div class="filter-field"><label for="from">Dari tanggal</label><input type="date" id="from" name="from" value="<?= e($from) ?>"></div>
  <div class="filter-field"><label for="to">Sampai tanggal</label><input type="date" id="to" name="to" value="<?= e($to) ?>"></div>
  <?php if ($r !== 'siswa'): ?><div class="filter-field"><label for="kelas">Kelas</label><select id="kelas" name="kelas">
        <option value="">Semua kelas</option><?php foreach ($classRows as $c): ?><option value="<?= e($c) ?>" <?= $kelas === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
      </select></div><?php endif; ?>
  <div class="filter-actions"><button class="primary-button" type="submit">Terapkan Filter</button><a class="reset-filter" href="index.php?page=reports">Reset</a></div>
</form>
<div class="report-summary">
  <article class="report-stat"><span>Jumlah pengumpulan</span><b><?= count($rows) ?></b><small>Data sesuai filter</small></article>
  <article class="report-stat"><span>Sudah dinilai</span><b><?= count($avgValues) ?></b><small>Memiliki nilai proyek</small></article>
  <article class="report-stat"><span>Rata-rata nilai</span><b><?= $average === null ? '—' : number_format($average, 2) ?></b><small>Empat indikator kreativitas</small></article>
</div>
<div class="panel report-table-panel">
  <div class="table-title">
    <div>
      <h3>Data laporan proyek</h3>
      <p class="muted">Menampilkan <?= count($rows) ?> baris data.</p>
    </div><button type="button" class="outline-button" onclick="window.print()">Cetak halaman</button>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><?php if ($r !== 'siswa'): ?><th>Nama siswa</th>
            <th>Kelas</th><?php endif; ?><th>Tanggal</th>
          <th>Proyek</th>
          <th>Status</th>
          <th>Fluency</th>
          <th>Flexibility</th>
          <th>Originality</th>
          <th>Elaboration</th>
          <th>Nilai akhir</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?><tr>
            <td colspan="<?= $r === 'siswa' ? 8 : 10 ?>" class="empty-cell">Belum ada data untuk filter yang dipilih.</td>
          </tr><?php endif; ?>
        <?php foreach ($rows as $x): ?><tr><?php if ($r !== 'siswa'): ?><td><?= e($x['siswa']) ?></td>
              <td><?= e($x['kelas']) ?></td><?php endif; ?><td><?= e(date('d-m-Y', strtotime($x['uploaded_at']))) ?></td>
            <td><?= e($x['judul']) ?></td>
            <td><span class="status-pill"><?= e($x['status_pengumpulan']) ?></span></td>
            <td><?= $x['fluency'] === null ? '—' : e($x['fluency']) ?></td>
            <td><?= $x['flexibility'] === null ? '—' : e($x['flexibility']) ?></td>
            <td><?= $x['originality'] === null ? '—' : e($x['originality']) ?></td>
            <td><?= $x['elaboration'] === null ? '—' : e($x['elaboration']) ?></td>
            <td><b><?= $x['nilai_akhir'] === null ? 'Belum dinilai' : e($x['nilai_akhir']) ?></b></td>
          </tr><?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="report-footnote">Laporan ini merangkum pengumpulan proyek dan penilaian yang sudah tercatat. Data pretest dan posttest tetap tersedia pada menu Pretest &amp; Posttest.</p>
</div>