<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/helpers.php';
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Akses ditolak.');
}
$id = (int)($_GET['id'] ?? 0);
$s = $pdo->prepare("SELECT u.*,p.guru_id FROM unggah_proyek u JOIN proyek p ON p.id=u.proyek_id WHERE u.id=?");
$s->execute([$id]);
$row = $s->fetch();
if (!$row) {
    http_response_code(404);
    exit('File tidak ditemukan.');
}
if (role() === 'siswa' && (int)$row['siswa_id'] !== (int)current_user()['id']) {
    http_response_code(403);
    exit('Akses ditolak.');
}
if (role() === 'guru' && (int)$row['guru_id'] !== (int)current_user()['id']) {
    http_response_code(403);
    exit('Akses ditolak.');
}
$path = UPLOAD_DIR . $row['nama_file'];
if (!is_file($path)) {
    http_response_code(404);
    exit('File tidak tersedia.');
}
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . rawurlencode($row['file_asli']) . '"');
header('Content-Length: ' . filesize($path));
readfile($path);
