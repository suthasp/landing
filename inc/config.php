<?php
declare(strict_types=1);

/**
 * ค่าคอนฟิกกลางของเว็บไซต์ (แก้ไขข้อมูลติดต่อ / อีเมลรับฟอร์ม ได้ที่นี่ที่เดียว)
 */
return [
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

    // การส่งฟอร์ม
    'mail' => [
        'enabled' => false,                      // true = ส่งอีเมลด้วย mail() ของ PHP
        'to'      => 'support@texson.co.th',
        'from'    => 'no-reply@texson.co.th',
    ],
    'leads_file'   => __DIR__ . '/../storage/leads.csv',
    'rate_limit_seconds' => 30,                  // กันกดส่งซ้ำถี่เกินไป
];
