<?php
declare(strict_types=1);

/**
 * ตั้งค่าเริ่มต้นของทุก request: session, config, ภาษา, ธีม, helper
 */

mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Bangkok');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$GLOBALS['CFG'] = require __DIR__ . '/config.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/mailer.php';

/* ---------- ภาษา: ?lang=xx > cookie > ค่าเริ่มต้น ---------- */
$languages = cfg('languages', ['th', 'en']);
$lang      = $_COOKIE['lang'] ?? cfg('default_lang', 'th');

if (isset($_GET['lang']) && in_array($_GET['lang'], $languages, true)) {
    $lang = (string)$_GET['lang'];
    setcookie('lang', $lang, [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'samesite' => 'Lax',
    ]);
}
if (!in_array($lang, $languages, true)) {
    $lang = cfg('default_lang', 'th');
}
$GLOBALS['LANG'] = $lang;

/* ---------- ธีม: ?theme=xx > cookie > ค่าเริ่มต้น (ทำงานได้แม้ปิด JS) ---------- */
$theme = $_COOKIE['theme'] ?? cfg('default_theme', 'light');
if (isset($_GET['theme']) && in_array($_GET['theme'], ['light', 'dark'], true)) {
    $theme = (string)$_GET['theme'];
    setcookie('theme', $theme, [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'samesite' => 'Lax',
    ]);
}
if (!in_array($theme, ['light', 'dark'], true)) {
    $theme = cfg('default_theme', 'light');
}
$GLOBALS['THEME'] = $theme;

/* ---------- โหลดไฟล์ภาษา ---------- */
$GLOBALS['L'] = require __DIR__ . '/../lang/' . $lang . '.php';

/* ---------- ค่าฟอร์มที่ค้างไว้ ---------- */
$GLOBALS['FORM_OLD']    = flash_get('form_old', []);
$GLOBALS['FORM_ERRORS'] = flash_get('form_errors', []);
$GLOBALS['FORM_STATUS'] = flash_get('form_status');
