<?php $u = current_user();
$r = role();
$menus = [['dashboard', 'Dashboard', 'D', ['admin', 'guru', 'siswa']], ['users', 'Manajemen Pengguna', 'U', ['admin']], ['projects', 'Proyek Pembelajaran', 'P', ['admin', 'guru', 'siswa']], ['submissions', $r === 'siswa' ? 'Pengumpulan Saya' : 'Pengumpulan Siswa', 'K', ['admin', 'guru', 'siswa']], ['monitoring', 'Monitoring Progres', 'M', ['admin', 'guru']], ['assessment', 'Penilaian Kreativitas', 'N', ['admin', 'guru']], ['tests', 'Pretest & Posttest', 'T', ['admin', 'guru', 'siswa']], ['reports', 'Laporan & Rekap', 'R', ['admin', 'guru', 'siswa']], ['profile', 'Profil Saya', 'A', ['admin', 'guru', 'siswa']]]; ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/style.css?v=redesign2">
</head>

<body class="app-body role-<?= e($r) ?>">
    <div class="app-shell">
        <aside class="app-sidebar"><a class="sidebar-brand" href="index.php"><span class="brand-mark">L</span><span>LPMS<small>LEARNING PROJECT MONITOR</small></span></a>
            <div class="sidebar-caption">MENU UTAMA</div>
            <nav class="side-nav"><?php foreach ($menus as [$key, $label, $icon, $roles]): if (!in_array($r, $roles, true)) continue; ?><a class="<?= ($page === $key ? 'active' : '') ?>" href="index.php?page=<?= $key ?>"><span class="nav-icon"><?= $icon ?></span><?= e($label) ?></a><?php endforeach; ?></nav>
            <div class="sidebar-bottom">
                <div class="sidebar-user"><span class="avatar"><?= e(strtoupper(substr($u['nama'] ?? 'U', 0, 1))) ?></span><span><b><?= e($u['nama'] ?? 'Pengguna') ?></b><small><?= e(ucfirst($r)) ?></small></span></div><a class="logout-link" href="index.php?page=logout">Keluar dari aplikasi ↗</a>
            </div>
        </aside>
        <main class="app-main">
            <header class="app-topbar">
                <div><small>LEARNING PROJECT MONITORING SYSTEM</small><b><?= e(ucfirst($r)) ?> Workspace</b></div>
                <div class="top-user"><i></i><?= e($u['nama'] ?? '') ?> <span><?= e(strtoupper($r)) ?></span></div>
            </header>
            <section class="page-content"><?php show_flash(); ?>