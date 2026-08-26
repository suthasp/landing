<?php
declare(strict_types=1);
/**
 * โลโก้ TE[X]SON — วาดด้วย SVG ล้วน ปรับสีตามธีมผ่านตัวแปร CSS
 * X = รูปทรงเหลี่ยมคม ซ้อนกัน 2 ชั้น (ชั้นหลังสีฟ้าเหลื่อมออกมา)
 */
$logoX = 'M4 9 13 4 24 17 35 4 44 9 31 24 44 39 35 44 24 31 13 44 4 39 17 24Z';
?>
<span class="logo" aria-label="TEXSON">
    <span class="logo__text">TE</span>
    <svg class="logo__mark" viewBox="0 0 48 48" role="presentation" focusable="false" aria-hidden="true">
        <path class="logo__x-echo" d="<?= $logoX ?>" transform="translate(4.5 -4.5)"/>
        <path class="logo__x" d="<?= $logoX ?>"/>
    </svg>
    <span class="logo__text logo__text--accent">SON</span>
</span>
