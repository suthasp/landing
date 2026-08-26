<?php
declare(strict_types=1);

/** escape สำหรับ output ใน HTML */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** ดึงข้อความจากไฟล์ภาษาแบบ dot notation เช่น t('hero.title') */
function t(string $path, $fallback = '')
{
    $node = $GLOBALS['L'] ?? [];
    foreach (explode('.', $path) as $key) {
        if (!is_array($node) || !array_key_exists($key, $node)) {
            return $fallback;
        }
        $node = $node[$key];
    }
    return $node;
}

/** ข้อความ + escape ในคำสั่งเดียว */
function te(string $path, string $fallback = ''): string
{
    $value = t($path, $fallback);
    return e(is_string($value) ? $value : $fallback);
}

function cfg(string $path, $fallback = null)
{
    $node = $GLOBALS['CFG'] ?? [];
    foreach (explode('.', $path) as $key) {
        if (!is_array($node) || !array_key_exists($key, $node)) {
            return $fallback;
        }
        $node = $node[$key];
    }
    return $node;
}

function current_lang(): string
{
    return $GLOBALS['LANG'] ?? cfg('default_lang', 'th');
}

function other_lang(): string
{
    return current_lang() === 'th' ? 'en' : 'th';
}

function current_theme(): string
{
    return $GLOBALS['THEME'] ?? cfg('default_theme', 'light');
}

/** สร้าง URL ปัจจุบันพร้อมเปลี่ยนค่า query string */
function url_with(array $params = []): string
{
    $path  = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
    $query = [];
    parse_str((string)($_SERVER['QUERY_STRING'] ?? ''), $query);
    $query = array_merge($query, $params);
    $query = array_filter($query, static fn($v) => $v !== null && $v !== '');
    return $path . ($query ? '?' . http_build_query($query) : '');
}

function asset(string $path): string
{
    $base = rtrim((string)cfg('base_url', ''), '/');
    $file = __DIR__ . '/../' . ltrim($path, '/');
    $ver  = is_file($file) ? (string)filemtime($file) : '1';
    return $base . '/' . ltrim($path, '/') . '?v=' . $ver;
}

/* ---------- session helpers ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valid(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function flash_set(string $key, $value): void
{
    $_SESSION['flash'][$key] = $value;
}

function flash_get(string $key, $fallback = null)
{
    if (!isset($_SESSION['flash'][$key])) {
        return $fallback;
    }
    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $value;
}

/** ค่าที่ผู้ใช้กรอกไว้ก่อนหน้า (กรณีฟอร์ม error) */
function old(string $key, string $fallback = ''): string
{
    $old = $GLOBALS['FORM_OLD'] ?? [];
    return e((string)($old[$key] ?? $fallback));
}

function form_error(string $key): ?string
{
    $errors = $GLOBALS['FORM_ERRORS'] ?? [];
    return isset($errors[$key]) ? (string)$errors[$key] : null;
}

/** แสดง partial */
function section(string $name): void
{
    require __DIR__ . '/../partials/' . $name . '.php';
}
