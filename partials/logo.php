<?php
declare(strict_types=1);
/**
 * โลโก้ TE[X]SON — SVG ล้วน ปรับสีตามธีมผ่านตัวแปร CSS
 * X = เส้นขอบรูปทรงเหลี่ยมคม (สีตัวอักษร) ครอบไส้ในเป็นกากบาทสีฟ้า
 */
$logoX = 'M4 9 13 4 24 17 35 4 44 9 31 24 44 39 35 44 24 31 13 44 4 39 17 24Z';
?>
<span class="logo" aria-label="TEXSON">
    <span class="logo__text">TE</span>
    <svg class="logo__mark" viewBox="0 0 48 48" role="presentation" focusable="false" aria-hidden="true">
        <path class="logo__x-outline" d="<?= $logoX ?>"/>
        <path class="logo__x-inner" d="M11 11 37 37M37 11 11 37"/>
    </svg>
    <span class="logo__text logo__text--accent">SON</span>
</span>
