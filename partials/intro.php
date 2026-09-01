<?php
declare(strict_types=1);
/**
 * อินโทรตอนเข้าเว็บ — ตัวอักษรแบรนด์เจาะทะลุแผ่นดำ เห็นภาพห้อง Server ข้างหลัง
 * พอเลื่อนลง ตัวอักษรจะขยายจนแผ่นดำหายไปหมดและเห็นภาพเต็มจอ
 *
 * ทำงานเฉพาะเมื่อเปิด JavaScript (CSS ซ่อนไว้จนกว่า <html> จะมีคลาส .js)
 * และข้ามให้อัตโนมัติเมื่อ prefers-reduced-motion หรือเข้าลิงก์ที่มี #anchor
 */

$introImage = (string)cfg('intro.image', '');
$introFile  = __DIR__ . '/../' . ltrim($introImage, '/');
$hasImage   = $introImage !== '' && is_file($introFile);
$introText  = (string)cfg('intro.text', 'TEXSON');
?>
<section class="intro" id="intro" aria-hidden="true">
    <div class="intro__stage">
        <div class="intro__media<?= $hasImage ? '' : ' intro__media--fallback' ?>" id="intro-media"
             <?= $hasImage ? 'style="background-image:url(' . e(asset($introImage)) . ')"' : '' ?>></div>

        <svg class="intro__mask" id="intro-mask">
            <defs>
                <!-- ขนาดทุกชิ้นกำหนดเป็นพิกเซลจริงจาก JS (ใช้ % แล้วบางเบราว์เซอร์ทิ้งหน้ากากตอนเลื่อน) -->
                <mask id="intro-cut" maskUnits="userSpaceOnUse" x="0" y="0" width="10" height="10">
                    <!-- ขาว = ทึบ (เห็นแผ่นดำ) / ดำ = เจาะทะลุ (เห็นภาพ) -->
                    <rect id="intro-fill" x="0" y="0" width="10" height="10" fill="#fff"/>
                    <g id="intro-word">
                        <text id="intro-text" text-anchor="middle" dominant-baseline="central"
                              fill="#000"><?= e($introText) ?></text>
                    </g>
                </mask>
            </defs>
            <rect id="intro-cover" x="0" y="0" width="10" height="10" fill="#000" mask="url(#intro-cut)"/>
        </svg>

        <p class="intro__hint" id="intro-hint">
            <span><?= te('intro.scroll') ?></span>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 9.5 12 15.5 18 9.5" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </p>
    </div>
</section>
