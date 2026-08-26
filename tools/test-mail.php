<?php
declare(strict_types=1);

/**
 * เครื่องมือทดสอบการส่งอีเมลบนโฮสต์จริง
 *
 * วิธีที่ 1 (มี SSH / Terminal ใน cPanel):
 *     php tools/test-mail.php someone@example.com
 *
 * วิธีที่ 2 (ไม่มี SSH): ตั้ง 'test_key' ใน inc/config.local.php ก่อน เช่น
 *     'mail' => ['test_key' => 'ค่าสุ่มยาวๆ'],
 * แล้วเปิด https://texson.co.th/tools/test-mail.php?key=ค่าสุ่มยาวๆ&to=someone@example.com
 *
 * ***** เสร็จแล้วลบไฟล์นี้ออกจากโฮสต์ หรือลบ test_key ทิ้ง *****
 */

require __DIR__ . '/../inc/bootstrap.php';

$isCli = PHP_SAPI === 'cli';

if (!$isCli) {
    header('Content-Type: text/plain; charset=UTF-8');
    $key = (string)cfg('mail.test_key', '');
    if ($key === '' || !hash_equals($key, (string)($_GET['key'] ?? ''))) {
        http_response_code(403);
        exit("ปฏิเสธการเข้าถึง — ต้องตั้ง mail.test_key ใน inc/config.local.php แล้วส่ง ?key= ให้ตรงกัน\n");
    }
}

$to = $isCli
    ? (string)($argv[1] ?? cfg('mail.to', ''))
    : (string)($_GET['to'] ?? cfg('mail.to', ''));

if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    exit("ระบุอีเมลผู้รับให้ถูกต้อง เช่น: php tools/test-mail.php you@example.com\n");
}

echo "== ตรวจการตั้งค่า ==\n";
printf("enabled   : %s\n", cfg('mail.enabled') ? 'true' : 'false');
printf("transport : %s\n", (string)cfg('mail.transport'));
printf("from      : %s\n", (string)cfg('mail.from'));
printf("to        : %s\n", $to);

if ((string)cfg('mail.transport') === 'smtp') {
    printf("smtp      : %s:%d (%s)\n", (string)cfg('mail.smtp.host'), (int)cfg('mail.smtp.port'), (string)cfg('mail.smtp.secure'));
    printf("smtp user : %s\n", (string)cfg('mail.smtp.user'));
    printf("รหัสผ่าน   : %s\n", cfg('mail.smtp.pass') !== '' ? 'ตั้งค่าแล้ว' : '*** ยังไม่ได้ตั้ง ***');
}

if (!cfg('mail.enabled')) {
    echo "\nหมายเหตุ: mail.enabled = false อยู่ (ฟอร์มจริงจะยังไม่ส่งเมล) แต่จะทดสอบส่งให้เลย\n";
}

echo "\n== กำลังส่ง ==\n";
$start = microtime(true);

[$ok, $error] = mailer_send(
    $to,
    (string)cfg('mail.subject_prefix', '[TEXSON] ') . 'ทดสอบการส่งอีเมล',
    implode("\n", [
        'นี่คืออีเมลทดสอบจากเว็บไซต์ TEXSON',
        '',
        'เวลา   : ' . date('d/m/Y H:i:s') . ' น.',
        'โฮสต์  : ' . (gethostname() ?: '-'),
        'PHP    : ' . PHP_VERSION,
        'ช่องทาง : ' . (string)cfg('mail.transport'),
        '',
        'ถ้าได้รับฉบับนี้ แปลว่าการตั้งค่าอีเมลถูกต้องแล้ว',
    ])
);

printf("ใช้เวลา %.2f วินาที\n", microtime(true) - $start);

if ($ok) {
    echo "\nสำเร็จ — ส่งออกไปแล้ว ลองเช็คกล่องขาเข้า (และโฟลเดอร์สแปม)\n";
    exit(0);
}

echo "\nไม่สำเร็จ: " . $error . "\n\n";
echo "จุดที่มักเป็นสาเหตุบนแชร์โฮสต์ไทย:\n";
echo "  - รหัสผ่านเมลบ็อกซ์ผิด หรือยังไม่ได้สร้าง no-reply@ ใน cPanel\n";
echo "  - พอร์ตถูกบล็อก ลองสลับ 465+ssl กับ 587+tls หรือใช้ host = localhost\n";
echo "  - โฮสต์ปิด stream_socket_client / openssl (ให้ใช้ transport = 'mail' แทน)\n";
echo "  - ดูบันทึกเพิ่มเติมที่ storage/mail.log\n";
exit(1);
