# TEXSON — Landing Page (Pure PHP)

เว็บ Landing Page ของ TEXSON (ที่ปรึกษา Data Center Facility) เขียนด้วย PHP ล้วน
ไม่มี framework, ไม่มี composer, ไม่มี build step — วางไฟล์ลง web server ที่มี PHP 8+ แล้วใช้งานได้ทันที

## รันบนเครื่อง

```bash
php -S localhost:8000
# เปิด http://localhost:8000
```

หรือวางทั้งโฟลเดอร์ไว้ใน `htdocs` ของ XAMPP / Laragon แล้วเปิดผ่าน Apache ได้เลย
ถ้าไม่ได้วางไว้ที่ root ให้ตั้ง `base_url` ใน `inc/config.php` เช่น `'/landingtexson'`

## โครงสร้าง

| ไฟล์ / โฟลเดอร์ | หน้าที่ |
|---|---|
| `index.php` | หน้าเดียวของเว็บ — ประกอบ section ทั้งหมด |
| `inc/config.php` | ข้อมูลติดต่อ, ตั้งค่าอีเมล/SMTP, ภาษา/ธีมเริ่มต้น |
| `inc/config.local.php` | ค่าลับเฉพาะโฮสต์ (รหัสผ่าน SMTP) — ไม่ถูก commit |
| `inc/mailer.php` | ตัวส่งอีเมล PHP ล้วน รองรับ SMTP (SSL/TLS) และ mail() |
| `tools/test-mail.php` | สคริปต์ทดสอบส่งอีเมลบนโฮสต์จริง |
| `tools/diagnose.php` | ตรวจว่า "ทำไมเมลไม่เข้า" (ค่าตั้ง, การต่อ SMTP, สิทธิ์เขียนไฟล์) |
| `inc/bootstrap.php` | session, เลือกภาษา/ธีม, โหลดไฟล์ภาษา |
| `inc/helpers.php` | `e()`, `t()`, `cfg()`, CSRF, flash message |
| `inc/contact_handler.php` | รับ POST จากฟอร์ม → ตรวจสอบ → บันทึก → redirect |
| `lang/th.php`, `lang/en.php` | ข้อความทั้งหมดของเว็บ (แก้คอนเทนต์ที่นี่) |
| `partials/*.php` | แต่ละ section: intro, header, hero, problems, services, products, why, process, contact, footer |
| `assets/` | CSS / JS / โลโก้ / favicon |
| `storage/leads.csv` | รายชื่อผู้ติดต่อที่ส่งฟอร์มเข้ามา (สร้างอัตโนมัติ) |

## ฟีเจอร์

- **อินโทรหน้าแรก** — ตัวอักษร `TEXSON` เจาะทะลุเห็นภาพห้อง Server เลื่อนลงแล้วขยายจนเต็มจอก่อนเข้าเว็บ
  ตั้งค่าที่ `intro` ใน `inc/config.php` (เปิด/ปิด, ข้อความ, รูป) ใส่รูปที่ `assets/img/intro-bg.jpg`
  ถ้าไม่มีรูปจะใช้พื้นหลังไล่สีแทน และข้ามอินโทรอัตโนมัติเมื่อเข้าลิงก์ที่มี `#anchor` หรือปิด JS
- **สองภาษา TH / EN** — `?lang=th|en` แล้วจำด้วย cookie 1 ปี ข้อความทั้งหมดอยู่ใน `lang/`
- **โหมดสว่าง / มืด** — ปุ่มบน header สลับทันทีด้วย JS และจำผ่าน cookie; ถ้าปิด JS ลิงก์ `?theme=` ก็ยังทำงาน
- **ฟอร์มติดต่อใช้งานได้จริง** — ตรวจข้อมูลฝั่ง server, CSRF token, honeypot กันบอท,
  จำกัดการส่งซ้ำ 30 วินาที, บันทึกลง CSV (มี BOM เปิดใน Excel อ่านภาษาไทยได้)
  และใช้รูปแบบ POST/Redirect/GET กันส่งซ้ำเวลา refresh
- **Responsive + accessible** — เมนูมือถือ, skip link, `prefers-reduced-motion`, print stylesheet
- **URL ผิดไม่เจอหน้า Not Found** — พิมพ์ลิงก์ผิดหรือเข้าโฟลเดอร์ที่ไม่มีจริง จะถูกส่งกลับหน้าแรก (ตั้งค่าใน `.htaccess`)
  ยกเว้นไฟล์สื่อ/สคริปต์ (`.css`, `.js`, รูป ฯลฯ) ที่ยังตอบ 404 ตามปกติเพื่อให้ดีบักได้
- **ไม่พึ่ง JavaScript** — ปิด JS แล้วยังอ่านและส่งฟอร์มได้ครบ

## ตั้งค่าส่งอีเมล (แชร์โฮสต์ไทย / cPanel / DirectAdmin)

เว็บนี้ส่งอีเมลเอง ไม่ผ่านบริการภายนอก จึงคุมเนื้อความได้ทั้งฉบับ ไม่มีข้อความหรือแบรนด์ของผู้ให้บริการติดมา

**1. สร้างเมลบ็อกซ์ผู้ส่งใน cPanel** → Email Accounts → สร้าง `no-reply@texson.co.th` แล้วจดรหัสผ่านไว้
(ต้องส่งจากเมลบ็อกซ์จริงในโดเมนเดียวกัน ไม่งั้น SPF/DKIM ไม่ผ่านและจะตกสแปม)

**2. คัดลอกไฟล์ค่าลับ**

```bash
cp inc/config.local.example.php inc/config.local.php
```

แล้วแก้ค่าในนั้น (ไฟล์นี้ถูก .gitignore ไว้ จะไม่ถูก commit):

```php
'mail' => [
    'enabled' => true,
    'to'      => 'support@texson.co.th',
    'smtp'    => [
        'host'   => 'mail.texson.co.th',   // หรือ 'localhost' ถ้าเว็บกับเมลอยู่โฮสต์เดียวกัน
        'port'   => 465,                   // 465+ssl (แนะนำ) | 587+tls | 25+none
        'secure' => 'ssl',
        'user'   => 'no-reply@texson.co.th',
        'pass'   => 'รหัสผ่านเมลบ็อกซ์',
    ],
],
```

**3. ทดสอบก่อนใช้จริง**

```bash
php tools/test-mail.php you@example.com
```

ไม่มี SSH? ตั้ง `'test_key' => 'ค่าสุ่มยาวๆ'` ใต้ `mail` ใน `config.local.php` แล้วเปิด
`https://texson.co.th/tools/test-mail.php?key=ค่าสุ่มยาวๆ&to=you@example.com`
— **เสร็จแล้วลบไฟล์ `tools/test-mail.php` หรือลบ `test_key` ทิ้ง**

### ถ้าส่งไม่ผ่าน / เมลไม่เข้า

รันตัวตรวจก่อนเป็นอันดับแรก — จะบอกสาเหตุให้เลย:

```bash
php tools/diagnose.php
```


| อาการ | วิธีแก้ |
|---|---|
| ต่อพอร์ตไม่ได้ / timeout | สลับ `465 + ssl` ↔ `587 + tls` หรือใช้ `host => 'localhost'` |
| Authentication failed | ตรวจว่า `user` เป็นอีเมลเต็ม และรหัสผ่านตรงกับที่ตั้งใน cPanel |
| โฮสต์ปิด `stream_socket_client` / openssl | เปลี่ยนเป็น `'transport' => 'mail'` (ใช้ `mail()` ของ PHP) |
| เมลเข้าแต่ตกสแปม | เพิ่ม SPF/DKIM ของโฮสต์ใน DNS ของโดเมน |

รายละเอียดข้อผิดพลาดทุกครั้งถูกบันทึกไว้ที่ `storage/mail.log`

หมายเหตุ: ทุกครั้งที่มีคนส่งฟอร์ม ข้อมูลจะถูกบันทึกลง `storage/leads.csv` เสมอ
แม้อีเมลจะส่งไม่สำเร็จก็ไม่มีข้อมูลลูกค้าหาย

## แก้เนื้อหา

- ข้อความ/หัวข้อ/รายการทั้งหมด → `lang/th.php` และ `lang/en.php` (โครงสร้าง key เหมือนกันทั้งสองไฟล์)
- เบอร์โทร อีเมล เวลาทำการ → `inc/config.php` (แสดงผลทั้งส่วนติดต่อและ footer)
- สี/ฟอนต์ → ตัวแปร CSS ด้านบนของ `assets/css/style.css` (`--brand`, `--navy`, `--bg` ...)
- โลโก้ → `assets/img/logo-light.png` (พื้นสว่าง) และ `logo-dark.png` (พื้นมืด) สลับอัตโนมัติตามธีม
  ไฟล์ต้นฉบับที่ยังไม่ครอปคือ `dark-preview.png` / `light-preview.png`
  ไฟล์อื่นที่แตกมาจากโลโก้: `logo-mark.png` (ตัว X อย่างเดียว), `favicon-128.png`, `logo-banner.png`

## หมายเหตุด้านความปลอดภัย

- โฟลเดอร์ `storage/` มี `.htaccess` กันเข้าถึงไฟล์ leads โดยตรง — ถ้าใช้ **Nginx** ต้องตั้ง deny เอง เช่น
  `location ^~ /storage/ { deny all; }`
- ควรวางเฉพาะ `index.php` และ `assets/` ไว้ใน document root หากต้องการความปลอดภัยสูงสุด
