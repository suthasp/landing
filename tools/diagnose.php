<?php
declare(strict_types=1);

/**
 * ตรวจสุขภาพระบบส่งเมล — บอกว่า "ทำไมเมลไม่เข้า"
 *
 * CLI:  php tools/diagnose.php
 * เว็บ: ตั้ง 'test_key' ใน inc/config.local.php แล้วเปิด
 *       https://โดเมนของคุณ/tools/diagnose.php?key=ค่าที่ตั้งไว้
 *
 * ***** ตรวจเสร็จแล้วลบไฟล์นี้ออกจากโฮสต์ *****
 */

require __DIR__ . '/../inc/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=UTF-8');
    $key = (string)cfg('mail.test_key', '');
    if ($key === '' || !hash_equals($key, (string)($_GET['key'] ?? ''))) {
        http_response_code(403);
        exit("ปฏิเสธการเข้าถึง — ต้องตั้ง mail.test_key ใน inc/config.local.php แล้วส่ง ?key= ให้ตรงกัน\n");
    }
}

$problems = [];
$line = static fn(string $label, string $value): string => sprintf("  %-26s %s\n", $label, $value);

echo "===== TEXSON · ตรวจระบบส่งเมล =====\n\n";

/* 1. สภาพแวดล้อม */
echo "[1] สภาพแวดล้อม\n";
echo $line('PHP', PHP_VERSION . ' (' . PHP_SAPI . ')');
echo $line('openssl', extension_loaded('openssl') ? 'มี' : 'ไม่มี');
echo $line('stream_socket_client()', function_exists('stream_socket_client') ? 'ใช้ได้' : 'ถูกปิด');
echo $line('mail()', function_exists('mail') ? 'ใช้ได้' : 'ถูกปิด');

if (!extension_loaded('openssl')) {
    $problems[] = 'โฮสต์ไม่มี openssl → ใช้ SMTP แบบ ssl/tls ไม่ได้ ให้ตั้ง transport = mail';
}
if (!function_exists('stream_socket_client')) {
    $problems[] = 'โฮสต์ปิด stream_socket_client() → ต่อ SMTP ไม่ได้ ให้ตั้ง transport = mail';
}

/* 2. ไฟล์ค่าลับ */
echo "\n[2] ไฟล์ตั้งค่า\n";
$localFile = __DIR__ . '/../inc/config.local.php';
$hasLocal  = is_file($localFile);
echo $line('inc/config.local.php', $hasLocal ? 'มี' : '*** ไม่มี ***');
if (!$hasLocal) {
    $problems[] = 'ยังไม่ได้สร้าง inc/config.local.php (คัดลอกจาก inc/config.local.example.php) '
        . '→ mail.enabled ยังเป็น false ระบบจึงไม่ส่งเมลเลย';
}

/* 3. ค่าการส่งเมล */
echo "\n[3] ค่าการส่งเมล\n";
$enabled   = (bool)cfg('mail.enabled');
$transport = (string)cfg('mail.transport', 'mail');
echo $line('mail.enabled', $enabled ? 'true' : '*** false (ไม่ส่งเมล) ***');
echo $line('mail.transport', $transport);
echo $line('mail.from', (string)cfg('mail.from', '-'));
echo $line('mail.to', (string)cfg('mail.to', '-'));

if (!$enabled) {
    $problems[] = 'mail.enabled = false → ฟอร์มบันทึกลง leads.csv อย่างเดียว ไม่ส่งเมล';
}

if ($transport === 'smtp') {
    $host = (string)cfg('mail.smtp.host', '');
    $port = (int)cfg('mail.smtp.port', 0);
    $sec  = (string)cfg('mail.smtp.secure', '');
    $pass = (string)cfg('mail.smtp.pass', '');
    echo $line('smtp.host:port', $host . ':' . $port . ' (' . $sec . ')');
    echo $line('smtp.user', (string)cfg('mail.smtp.user', '-'));
    echo $line('smtp.pass', $pass !== '' ? 'ตั้งค่าแล้ว (' . strlen($pass) . ' ตัวอักษร)' : '*** ยังไม่ได้ตั้ง ***');

    if ($pass === '') {
        $problems[] = 'ยังไม่ได้ใส่รหัสผ่าน SMTP ใน inc/config.local.php';
    }

    /* 4. ลองต่อจริง */
    echo "\n[4] ทดสอบต่อ SMTP\n";
    if ($host === '' || !function_exists('stream_socket_client')) {
        echo $line('ผล', 'ข้าม (ยังไม่ได้ตั้ง host หรือโฮสต์ปิด socket)');
    } else {
        $endpoint = ($sec === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
        $start    = microtime(true);
        $socket   = @stream_socket_client($endpoint, $errNo, $errStr, 10);
        $elapsed  = sprintf('%.2f วิ', microtime(true) - $start);

        if ($socket) {
            $greeting = trim((string)fgets($socket, 1024));
            @fwrite($socket, "QUIT\r\n");
            @fclose($socket);
            echo $line('ต่อ ' . $endpoint, 'สำเร็จ (' . $elapsed . ')');
            echo $line('เซิร์ฟเวอร์ตอบ', $greeting !== '' ? $greeting : '(ไม่ตอบ)');
            if ($greeting === '' || strpos($greeting, '220') !== 0) {
                $problems[] = 'ต่อพอร์ตได้แต่ไม่ใช่ SMTP ที่พร้อมใช้งาน — ลองสลับ 465+ssl ↔ 587+tls';
            }
        } else {
            echo $line('ต่อ ' . $endpoint, 'ล้มเหลว: ' . trim($errStr . ' (' . $errNo . ')'));
            $problems[] = 'ต่อ ' . $endpoint . ' ไม่ได้ — ลองสลับ 465+ssl ↔ 587+tls '
                . 'หรือใช้ host = localhost (ถ้าเว็บกับเมลอยู่โฮสต์เดียวกัน)';
        }
    }
}

/* 5. ไฟล์บันทึก */
echo "\n[5] ไฟล์บันทึก\n";
$leads = (string)cfg('leads_file', '');
$log   = (string)cfg('mail.log_file', '');
$dir   = dirname($leads);

// ทดสอบเขียนไฟล์จริง แม่นยำกว่า is_writable() (ซึ่งเชื่อถือไม่ได้บน Windows)
@mkdir($dir, 0775, true);
$probe    = $dir . '/.write-test';
$canWrite = @file_put_contents($probe, 'ok') !== false;
@unlink($probe);

echo $line('storage/ เขียนได้', $canWrite ? 'ได้' : '*** ไม่ได้ (chmod 775) ***');
echo $line('leads.csv', is_file($leads) ? 'มี ' . max(0, count(file($leads)) - 1) . ' รายการ' : 'ยังไม่มี');
if (!$canWrite) {
    $problems[] = 'โฟลเดอร์ storage/ เขียนไม่ได้ → ข้อมูลฟอร์มจะหาย ให้ chmod 775';
}

if (is_file($log)) {
    echo "\n  10 บรรทัดล่าสุดใน mail.log:\n";
    $lines = array_slice(file($log, FILE_IGNORE_NEW_LINES) ?: [], -10);
    foreach ($lines as $entry) {
        echo '    ' . $entry . "\n";
    }
} else {
    echo $line('mail.log', 'ยังไม่มี (ยังไม่เคยพยายามส่งเมล)');
}

/* สรุป */
echo "\n===== สรุป =====\n";
if (!$problems) {
    echo "ไม่พบปัญหาในการตั้งค่า — ลองส่งจริงด้วย: php tools/test-mail.php you@example.com\n";
    exit(0);
}
foreach ($problems as $i => $problem) {
    echo ($i + 1) . '. ' . $problem . "\n";
}
exit(1);
