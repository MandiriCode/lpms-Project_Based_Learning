<?php
$projectCount = (int)$pdo->query("SELECT COUNT(*) FROM proyek WHERE status='aktif'")->fetchColumn();
$studentCount = (int)$pdo->query("SELECT COUNT(*) FROM `user` WHERE role='siswa'")->fetchColumn();
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>LPMS · Learning Project Monitoring System</title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/style.css?v=final1">
</head>

<body class="public-body">
  <div class="landing">
    <nav class="landing-nav">
      <a class="landing-brand" href="index.php"><span class="brand-mark">L</span><span>LPMS<small>PROJECT LEARNING MONITOR</small></span></a>
      <div class="landing-nav-links"><a href="#tentang">Tentang</a><a href="#fitur">Fitur</a><a class="nav-login" href="index.php?page=login&role=guru">Masuk</a></div>
    </nav>
    <section class="hero">
      <div class="hero-copy">
        <div class="eyebrow"><span></span> PLATFORM PEMBELAJARAN BERBASIS PROYEK</div>
        <h1>Belajar dengan ide.<br><em>Berkarya dengan kreativitas.</em></h1>
        <p class="hero-lead">Ruang digital untuk merancang proyek, memantau proses belajar, dan mengembangkan keterampilan berpikir kreatif siswa.</p>
        <div class="hero-actions">
          <a class="role-button teacher" href="index.php?page=login&role=guru"><span class="role-icon">G</span><span><b>Login Guru</b><small>Kelola proyek & pantau perkembangan</small></span><span class="arrow">↗</span></a>
          <a class="role-button student" href="index.php?page=login&role=siswa"><span class="role-icon">S</span><span><b>Login Siswa</b><small>Ikuti proyek & kumpulkan karya</small></span><span class="arrow">↗</span></a>
        </div>
        <div class="hero-note"><span class="check-dot">✓</span> Satu platform untuk proses belajar yang lebih terarah dan terukur.</div>
      </div>
      <div class="hero-art" aria-label="Ilustrasi pembelajaran berbasis proyek">
        <div class="art-glow"></div>
        <div class="orbit orbit-one"></div>
        <div class="orbit orbit-two"></div>
        <div class="floating-card card-top"><span class="mini-icon purple">✦</span><span><b>Ide kreatif</b><small>Mulai dari rasa ingin tahu</small></span></div>
        <div class="project-board">
          <div class="board-top"><span class="board-dot"></span><span class="board-dot"></span><span class="board-dot"></span><small>PROJECT SPACE</small></div>
          <div class="board-content">
            <div class="board-label">LEARNING JOURNEY</div>
            <h3>Wujudkan<br>gagasanmu.</h3>
            <div class="board-shapes"><span class="shape shape-a"></span><span class="shape shape-b"></span><span class="shape shape-c">✦</span></div>
            <div class="board-progress"><span>Proses berkarya</span><b>75%</b><i><span></span></i></div>
          </div>
        </div>
        <div class="floating-card card-bottom"><span class="mini-icon orange">↗</span><span><b>Progres terpantau</b><small>Setiap langkah berarti</small></span><span class="pulse"></span></div>
        <div class="spark spark-a">✳</div>
        <div class="spark spark-b">✦</div>
        <div class="spark spark-c">✧</div>
      </div>
    </section>
    <section class="feature-strip" id="fitur">
      <div class="strip-intro"><span class="eyebrow">DALAM SATU PLATFORM</span>
        <h2>Proses belajar yang<br>terlihat dan berkembang.</h2>
      </div>
      <div class="feature"><span class="feature-icon violet">01</span><b>Kelola Proyek</b>
        <p>Guru menyusun aktivitas dan arahan proyek pembelajaran.</p>
      </div>
      <div class="feature"><span class="feature-icon peach">02</span><b>Monitoring Progres</b>
        <p>Perkembangan pengerjaan proyek tercatat secara berkala.</p>
      </div>
      <div class="feature"><span class="feature-icon mint">03</span><b>Ukur Kreativitas</b>
        <p>Penilaian melalui empat indikator berpikir kreatif.</p>
      </div>
    </section>
    <section class="about-band" id="tentang">
      <div><span class="eyebrow">TENTANG LPMS</span>
        <h2>Dari proses menjadi<br>hasil pembelajaran.</h2>
      </div>
      <p>LPMS mendukung pelaksanaan <b>Project Based Learning</b> melalui pengelolaan proyek, pengumpulan hasil karya, monitoring guru, serta pencatatan pretest dan posttest. Fokus penilaian keterampilan berpikir kreatif mencakup <b>Fluency, Flexibility, Originality,</b> dan <b>Elaboration.</b></p>
    </section>
    <footer class="landing-footer"><span>© <?= date('Y') ?> LPMS · Learning Project Monitoring System</span><a href="index.php?page=login&role=admin">Login Administrator</a></footer>
  </div>
</body>

</html>