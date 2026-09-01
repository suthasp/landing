<?php
declare(strict_types=1);

/**
 * ค่าคอนฟิกกลางของเว็บไซต์
 *
 * ค่าลับ (รหัสผ่าน SMTP) ให้ใส่ในไฟล์ inc/config.local.php แทน — ไฟล์นั้นไม่ถูก commit
 * ดูตัวอย่างที่ inc/config.local.example.php
 */

$config = [
    'brand'        => 'TEXSON',
    'base_url'     => '',                        // เช่น '/landingtexson' ถ้าไม่ได้วางไว้ที่ root
    'default_lang' => 'th',
    'languages'    => ['th', 'en'],
    'default_theme'=> 'light',                   // light | dark

    'contact' => [
        'phone'      => '099-989-8888',
        'phone_tel'  => '+66999898888',
        'email'      => 'support@texson.co.th',
        'line_id'    => '@texson',
    ],

    // ระบบอีเมลของบริษัท (Roundcube บนโฮสต์) — เว้นว่างไว้ถ้าไม่ต้องการแสดงลิงก์
    'webmail_url'  => 'https://texson.co.th/roundcube/',

    // การส่งอีเมลแจ้งเตือนเมื่อมีคนกรอกฟอร์ม
    'mail' => [
        'enabled'   => false,                    // true = ส่งอีเมล (ตั้งค่า SMTP ให้ครบก่อน)
        'transport' => 'smtp',                   // smtp = แนะนำ | mail = ใช้ mail() ของ PHP
        'to'        => 'support@texson.co.th',   // ผู้รับแจ้งเตือน
        'from'      => 'support@texson.co.th',   // ต้องตรงกับบัญชีที่ล็อกอิน SMTP
        'from_name' => 'TEXSON Website',
        'subject_prefix' => '[TEXSON] ',

        // ค่าของแชร์โฮสต์ไทยทั่วไป (cPanel / DirectAdmin)
        'smtp' => [
            'host'    => 'mail.texson.co.th',    // หรือ localhost ถ้าเว็บอยู่โฮสต์เดียวกับเมล
            'port'    => 587,                    // 465 = ssl, 587 = tls, 25 = none
            'secure'  => 'tls',                  // ssl | tls | none
            'user'    => 'support@texson.co.th', // ชื่อผู้ใช้ = อีเมลเต็ม
            'pass'    => '',                     // ใส่ใน inc/config.local.php
            'timeout' => 20,
        ],
    ],

    'leads_file'   => __DIR__ . '/../storage/leads.csv',
    'rate_limit_seconds' => 30,                  // กันกดส่งซ้ำถี่เกินไป
];

/* ทับค่าด้วยไฟล์ลับเฉพาะเครื่อง/โฮสต์ (ถ้ามี) */
$localFile = __DIR__ . '/config.local.php';
if (is_file($localFile)) {
    $local = require $localFile;
    if (is_array($local)) {
        $config = array_replace_recursive($config, $local);
    }
}

$config['mail']['log_file'] = $config['mail']['log_file'] ?? __DIR__ . '/../storage/mail.log';

return $config;
