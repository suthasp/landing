<?php
declare(strict_types=1);

/**
 * รับข้อมูลจากฟอร์มติดต่อ (POST) — ตรวจสอบ, บันทึกลง CSV, ส่งอีเมล (ถ้าเปิดใช้)
 * ใช้รูปแบบ POST/Redirect/GET กันการส่งซ้ำเมื่อกด refresh
 */

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || ($_POST['form'] ?? '') !== 'contact') {
    return;
}

$msg    = t('contact.messages', []);
$errors = [];

$name    = trim((string)($_POST['name'] ?? ''));
$company = trim((string)($_POST['company'] ?? ''));
$contact = trim((string)($_POST['contact'] ?? ''));
$service = trim((string)($_POST['service'] ?? ''));
$details = trim((string)($_POST['details'] ?? ''));
$website = trim((string)($_POST['website'] ?? ''));   // honeypot: บอทมักกรอก

$old = [
    'name'    => $name,
    'company' => $company,
    'contact' => $contact,
    'service' => $service,
    'details' => $details,
];

/* ---------- ตรวจสอบความปลอดภัย ---------- */
if (!csrf_valid($_POST['csrf'] ?? null)) {
    $errors['form'] = $msg['csrf'] ?? 'Invalid session.';
}

$last = (int)($_SESSION['last_submit'] ?? 0);
if (!$errors && $last && (time() - $last) < (int)cfg('rate_limit_seconds', 30)) {
    $errors['form'] = $msg['throttle'] ?? 'Please wait before sending again.';
}

/* ---------- ตรวจสอบข้อมูล ---------- */
if ($name === '' || mb_strlen($name) > 120) {
    $errors['name'] = $msg['name_req'] ?? 'Name is required.';
}

if ($contact === '') {
    $errors['contact'] = $msg['contact_req'] ?? 'Contact is required.';
} else {
    $isEmail = (bool)filter_var($contact, FILTER_VALIDATE_EMAIL);
    $digits  = preg_replace('/\D+/', '', $contact) ?? '';
    $isPhone = strlen($digits) >= 8 && strlen($digits) <= 15;
    if (!$isEmail && !$isPhone) {
        $errors['contact'] = $msg['contact_bad'] ?? 'Invalid phone or email.';
    }
}

if (mb_strlen($details) > 2000) {
    $errors['details'] = $msg['details_long'] ?? 'Details are too long.';
}

$options = (array)t('contact.form.service_options', []);
if ($service !== '' && !array_key_exists($service, $options)) {
    $service = '';
}

/* honeypot ติด = ทำเหมือนส่งสำเร็จ แต่ไม่บันทึกอะไร */
$isBot = $website !== '';

/* ---------- ผลลัพธ์ ---------- */
if ($errors && !$isBot) {
    flash_set('form_errors', $errors);
    flash_set('form_old', $old);
    flash_set('form_status', 'error');
} else {
    if (!$isBot) {
        $record = [
            date('Y-m-d H:i:s'),
            current_lang(),
            $name,
            $company,
            $contact,
            $options[$service] ?? '-',
            preg_replace('/\s+/u', ' ', $details) ?? '',
            $_SERVER['REMOTE_ADDR'] ?? '',
        ];

        $file = (string)cfg('leads_file');
        if ($file !== '') {
            @mkdir(dirname($file), 0775, true);
            $isNew = !is_file($file);
            if ($handle = @fopen($file, 'a')) {
                if (flock($handle, LOCK_EX)) {
                    if ($isNew) {
                        fwrite($handle, "\xEF\xBB\xBF"); // BOM ให้ Excel อ่านภาษาไทยได้
                        fputcsv($handle, ['datetime', 'lang', 'name', 'company', 'contact', 'service', 'details', 'ip']);
                    }
                    fputcsv($handle, $record);
                    fflush($handle);
                    flock($handle, LOCK_UN);
                }
                fclose($handle);
            }
        }

        if (cfg('mail.enabled')) {
            $subject = '[TEXSON] New enquiry from ' . $name;
            $body    = implode("\n", [
                'Name:    ' . $name,
                'Company: ' . ($company !== '' ? $company : '-'),
                'Contact: ' . $contact,
                'Service: ' . ($options[$service] ?? '-'),
                'Lang:    ' . current_lang(),
                'Time:    ' . date('Y-m-d H:i:s'),
                '',
                'Details:',
                $details !== '' ? $details : '-',
            ]);
            $headers = implode("\r\n", [
                'From: TEXSON Website <' . cfg('mail.from') . '>',
                'Content-Type: text/plain; charset=UTF-8',
                'MIME-Version: 1.0',
            ]);
            @mail((string)cfg('mail.to'), '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
        }

        $_SESSION['last_submit'] = time();
    }

    flash_set('form_status', 'success');
}

/* ---------- Redirect กลับมาที่ส่วนติดต่อ ---------- */
header('Location: ' . url_with([]) . '#contact', true, 303);
exit;
