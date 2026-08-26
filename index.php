<?php
declare(strict_types=1);

/**
 * TEXSON — Landing Page (Pure PHP, ไม่ใช้ framework)
 * โครงสร้าง: inc/ = ระบบหลัก, lang/ = ข้อความสองภาษา, partials/ = แต่ละ section
 */

require __DIR__ . '/inc/bootstrap.php';
require __DIR__ . '/inc/contact_handler.php';   // ทำงานเฉพาะเมื่อเป็น POST แล้ว redirect

?>
<!DOCTYPE html>
<html lang="<?= e((string)t('html_lang', 'th')) ?>" data-theme="<?= e(current_theme()) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= te('meta.title') ?></title>
<meta name="description" content="<?= te('meta.description') ?>">
<meta name="theme-color" content="#0f2544">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= te('meta.title') ?>">
<meta property="og:description" content="<?= te('meta.description') ?>">
<meta property="og:locale" content="<?= current_lang() === 'th' ? 'th_TH' : 'en_US' ?>">
<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
<script>
/* ตั้งธีมก่อน render กันจอกระพริบ (ทำงานคู่กับ cookie ฝั่ง PHP) */
(function () {
  try {
    var m = document.cookie.match(/(?:^|;\s*)theme=(light|dark)/);
    if (m) { document.documentElement.setAttribute('data-theme', m[1]); }
    else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.setAttribute('data-theme', 'dark');
    }
  } catch (e) {}
})();
</script>
</head>
<body>
<a class="skip-link" href="#main"><?= current_lang() === 'th' ? 'ข้ามไปยังเนื้อหาหลัก' : 'Skip to main content' ?></a>

<?php section('header'); ?>

<main id="main">
    <?php
    section('hero');
    section('problems');
    section('services');
    section('products');
    section('why');
    section('process');
    section('contact');
    ?>
</main>

<?php section('footer'); ?>

<script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
