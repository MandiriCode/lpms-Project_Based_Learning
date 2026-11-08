<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/helpers.php';
$page = (string)($_GET['page'] ?? (isset($_SESSION['user_id']) ? 'dashboard' : 'home'));
if ($page === 'logout') {
    session_unset();
    session_destroy();
    header('Location: ' . rtrim(BASE_URL, '/') . '/');
    exit;
}
$public = ['home', 'login', 'install'];
if (!isset($_SESSION['user_id']) && !in_array($page, $public, true)) redirect('index.php?page=login');
if ($page === 'login' && isset($_SESSION['user_id'])) redirect('index.php');
$routes = ['home', 'login', 'install', 'dashboard', 'users', 'projects', 'submissions', 'monitoring', 'assessment', 'tests', 'reports', 'profile'];
if (!in_array($page, $routes, true)) {
    http_response_code(404);
    $page = 'not_found';
}
$file = __DIR__ . '/app/pages/' . $page . '.php';
// Jalankan ekspor laporan sebelum layout HTML dimuat agar file unduhan tidak tercampur markup.
if ($page === 'reports' && in_array((string)($_GET['export'] ?? ''), ['csv', 'excel'], true)) {
    require $file;
    exit;
}
if (in_array($page, $public, true) || $page === 'not_found') {
    require $file;
    exit;
}
require __DIR__ . '/app/layout/header.php';
require $file;
require __DIR__ . '/app/layout/footer.php';
