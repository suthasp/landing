<?php
declare(strict_types=1);

/**
 * ตัวอย่างไฟล์ค่าลับเฉพาะโฮสต์
 *
 * วิธีใช้: คัดลอกไฟล์นี้เป็น inc/config.local.php แล้วแก้ค่าให้ตรงกับโฮสต์จริง
 * (inc/config.local.php ถูก .gitignore ไว้แล้ว จะไม่ถูก commit ขึ้น git)
 *
 * ค่าที่ใส่ที่นี่จะทับค่าใน inc/config.php เฉพาะ key ที่ระบุ (array_replace_recursive)
 */

return [
    'mail' => [
        'enabled'   => true,
        'transport' => 'smtp',
        'to'        => 'support@texson.co.th',
        'from'      => 'support@texson.co.th',

        'smtp' => [
            // แชร์โฮสต์ไทยส่วนใหญ่ (cPanel / DirectAdmin) ใช้ค่าประมาณนี้
            //   host: mail.<โดเมนของคุณ>  หรือ localhost ถ้าเว็บกับเมลอยู่โฮสต์เดียวกัน
            //   465 + ssl  (แนะนำ)   |   587 + tls   |   25 + none
            'host'   => 'mail.texson.co.th',
            'port'   => 587,
            'secure' => 'tls',
            'user'   => 'support@texson.co.th',
            'pass'   => 'ใส่รหัสผ่านเมลบ็อกซ์ที่สร้างใน cPanel',
        ],
    ],
];
