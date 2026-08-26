<?php
declare(strict_types=1);

/**
 * ตัวส่งอีเมล PHP ล้วน — ไม่ต้องใช้ composer / PHPMailer
 *
 * รองรับ 2 ช่องทาง (ตั้งที่ mail.transport ใน config)
 *   'smtp' → ต่อ SMTP ของโฮสต์เอง (แนะนำ: ผ่าน SPF/DKIM ไม่ตกสแปม)
 *   'mail' → ใช้ฟังก์ชัน mail() ของ PHP
 *
 * ทุกฟังก์ชันคืนค่า [bool $ok, string $error]
 */

/** ส่งอีเมล 1 ฉบับ */
function mailer_send(string $to, string $subject, string $body, array $options = []): array
{
    $from      = trim((string)($options['from'] ?? cfg('mail.from', '')));
    $fromName  = trim((string)($options['from_name'] ?? cfg('mail.from_name', 'TEXSON')));
    $replyTo   = trim((string)($options['reply_to'] ?? ''));
    $transport = (string)cfg('mail.transport', 'mail');

    if ($to === '' || $from === '') {
        return [false, 'ยังไม่ได้ตั้งค่าอีเมลผู้รับหรือผู้ส่ง'];
    }

    $headers = [
        'Date'                      => date('r'),
        'Message-ID'                => '<' . bin2hex(random_bytes(12)) . '@' . mailer_domain($from) . '>',
        'From'                      => mailer_address($fromName, $from),
        'To'                        => $to,
        'Subject'                   => mailer_encode_header($subject),
        'MIME-Version'              => '1.0',
        'Content-Type'              => 'text/plain; charset=UTF-8',
        'Content-Transfer-Encoding' => 'base64',
    ];
    if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $headers['Reply-To'] = $replyTo;
    }

    // base64 ปลอดภัยกับภาษาไทยที่สุด (ไม่โดนตัดบรรทัด / เพี้ยนรหัส)
    $encodedBody = rtrim(chunk_split(base64_encode($body), 76, "\r\n"));

    $result = $transport === 'smtp'
        ? mailer_send_smtp($to, $from, $headers, $encodedBody)
        : mailer_send_native($to, $headers, $encodedBody);

    if (!$result[0]) {
        mailer_log('ส่งไม่สำเร็จ (' . $transport . ') → ' . $to . ' : ' . $result[1]);
    }
    return $result;
}

/* ---------- ช่องทาง mail() ---------- */

function mailer_send_native(string $to, array $headers, string $encodedBody): array
{
    if (!function_exists('mail')) {
        return [false, 'โฮสต์นี้ปิดฟังก์ชัน mail()'];
    }

    $subject = $headers['Subject'];
    $lines   = [];
    foreach ($headers as $name => $value) {
        if ($name === 'To' || $name === 'Subject') {
            continue;   // mail() ใส่ให้เอง
        }
        $lines[] = $name . ': ' . $value;
    }

    $sender = mailer_email_only($headers['From']);
    $ok = @mail($to, $subject, $encodedBody, implode("\r\n", $lines), '-f' . $sender);

    return $ok ? [true, ''] : [false, 'mail() คืนค่า false (ดู error log ของโฮสต์)'];
}

/* ---------- ช่องทาง SMTP ---------- */

function mailer_send_smtp(string $to, string $from, array $headers, string $encodedBody): array
{
    $host    = (string)cfg('mail.smtp.host', '');
    $port    = (int)cfg('mail.smtp.port', 587);
    $secure  = strtolower((string)cfg('mail.smtp.secure', 'tls'));   // ssl | tls | none
    $user    = (string)cfg('mail.smtp.user', '');
    $pass    = (string)cfg('mail.smtp.pass', '');
    $timeout = (int)cfg('mail.smtp.timeout', 20);

    if ($host === '') {
        return [false, 'ยังไม่ได้ตั้งค่า mail.smtp.host'];
    }

    $endpoint = ($secure === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
    $context  = stream_context_create([
        'ssl' => ['SNI_enabled' => true, 'peer_name' => $host],
    ]);

    $socket = @stream_socket_client($endpoint, $errNo, $errStr, $timeout, STREAM_CLIENT_CONNECT, $context);
    if (!$socket) {
        return [false, 'ต่อ ' . $endpoint . ' ไม่ได้: ' . trim($errStr . ' (' . $errNo . ')')];
    }
    stream_set_timeout($socket, $timeout);

    $helo = mailer_helo_name();
    $fail = null;

    try {
        [$ok, $err] = mailer_smtp_expect($socket, 220);
        if (!$ok) { throw new RuntimeException($err); }

        [$ok, $err] = mailer_smtp_cmd($socket, 'EHLO ' . $helo, 250);
        if (!$ok) {
            // เซิร์ฟเวอร์เก่าที่ไม่รองรับ EHLO
            [$ok, $err] = mailer_smtp_cmd($socket, 'HELO ' . $helo, 250);
            if (!$ok) { throw new RuntimeException($err); }
        }

        if ($secure === 'tls') {
            [$ok, $err] = mailer_smtp_cmd($socket, 'STARTTLS', 220);
            if (!$ok) { throw new RuntimeException('STARTTLS: ' . $err); }

            $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            if ($crypto !== true) { throw new RuntimeException('เปิด TLS ไม่สำเร็จ'); }

            [$ok, $err] = mailer_smtp_cmd($socket, 'EHLO ' . $helo, 250);
            if (!$ok) { throw new RuntimeException($err); }
        }

        if ($user !== '') {
            [$ok, $err] = mailer_smtp_cmd($socket, 'AUTH LOGIN', 334);
            if (!$ok) { throw new RuntimeException('AUTH LOGIN: ' . $err); }

            [$ok, $err] = mailer_smtp_cmd($socket, base64_encode($user), 334);
            if (!$ok) { throw new RuntimeException('ชื่อผู้ใช้ไม่ถูกต้อง: ' . $err); }

            [$ok, $err] = mailer_smtp_cmd($socket, base64_encode($pass), 235);
            if (!$ok) { throw new RuntimeException('รหัสผ่านไม่ถูกต้อง: ' . $err); }
        }

        [$ok, $err] = mailer_smtp_cmd($socket, 'MAIL FROM:<' . $from . '>', 250);
        if (!$ok) { throw new RuntimeException('MAIL FROM: ' . $err); }

        [$ok, $err] = mailer_smtp_cmd($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
        if (!$ok) { throw new RuntimeException('RCPT TO: ' . $err); }

        [$ok, $err] = mailer_smtp_cmd($socket, 'DATA', 354);
        if (!$ok) { throw new RuntimeException('DATA: ' . $err); }

        $lines = [];
        foreach ($headers as $name => $value) {
            $lines[] = $name . ': ' . $value;
        }
        $message = implode("\r\n", $lines) . "\r\n\r\n" . $encodedBody;

        fwrite($socket, mailer_dot_stuff($message) . "\r\n.\r\n");
        [$ok, $err] = mailer_smtp_expect($socket, 250);
        if (!$ok) { throw new RuntimeException('ปลายทางไม่รับข้อความ: ' . $err); }
    } catch (RuntimeException $e) {
        $fail = $e->getMessage();
    }

    @fwrite($socket, "QUIT\r\n");
    @fclose($socket);

    return $fail === null ? [true, ''] : [false, $fail];
}

/** ส่งคำสั่ง 1 บรรทัดแล้วรอรหัสตอบกลับ */
function mailer_smtp_cmd($socket, string $command, $expect): array
{
    if (@fwrite($socket, $command . "\r\n") === false) {
        return [false, 'ส่งคำสั่งไม่สำเร็จ'];
    }
    return mailer_smtp_expect($socket, $expect);
}

/** อ่านคำตอบ (รองรับหลายบรรทัดแบบ "250-...") แล้วเทียบรหัส */
function mailer_smtp_expect($socket, $expect): array
{
    $expect   = (array)$expect;
    $response = '';

    while (($line = fgets($socket, 1024)) !== false) {
        $response .= $line;
        // บรรทัดสุดท้ายคือรูปแบบ "250 ข้อความ" (ตัวที่ 4 เป็นช่องว่าง)
        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
        $meta = stream_get_meta_data($socket);
        if (!empty($meta['timed_out'])) {
            return [false, 'หมดเวลารอคำตอบจากเซิร์ฟเวอร์'];
        }
    }

    if ($response === '') {
        return [false, 'ไม่ได้รับคำตอบจากเซิร์ฟเวอร์'];
    }

    $code = (int)substr($response, 0, 3);
    if (!in_array($code, $expect, true)) {
        return [false, trim($response)];
    }
    return [true, ''];
}

/* ---------- ตัวช่วย ---------- */

/** บรรทัดที่ขึ้นต้นด้วย "." ต้องเติมเป็น ".." ตามมาตรฐาน SMTP */
function mailer_dot_stuff(string $message): string
{
    $message = str_replace(["\r\n", "\r", "\n"], "\n", $message);
    $message = str_replace("\n", "\r\n", $message);
    return preg_replace('/^\./m', '..', $message) ?? $message;
}

function mailer_encode_header(string $text): string
{
    return preg_match('/[\x80-\xFF]/', $text)
        ? '=?UTF-8?B?' . base64_encode($text) . '?='
        : $text;
}

function mailer_address(string $name, string $email): string
{
    $name = trim(str_replace(["\r", "\n"], '', $name));
    return $name === '' ? $email : mailer_encode_header($name) . ' <' . $email . '>';
}

function mailer_email_only(string $address): string
{
    return preg_match('/<([^>]+)>/', $address, $m) ? $m[1] : trim($address);
}

function mailer_domain(string $email): string
{
    $parts = explode('@', $email);
    return $parts[1] ?? 'localhost';
}

function mailer_helo_name(): string
{
    $name = (string)($_SERVER['SERVER_NAME'] ?? '');
    if ($name === '') {
        $name = (string)(gethostname() ?: 'localhost');
    }
    return preg_replace('/[^A-Za-z0-9.\-]/', '', $name) ?: 'localhost';
}

/** บันทึกปัญหาการส่งเมลไว้ตรวจย้อนหลัง */
function mailer_log(string $message): void
{
    $file = (string)cfg('mail.log_file', '');
    if ($file === '') {
        return;
    }
    @mkdir(dirname($file), 0775, true);
    @file_put_contents($file, date('Y-m-d H:i:s') . ' | ' . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
}
