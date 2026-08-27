<?php
declare(strict_types=1);
/**
 * โลโก้ TEXSON — ใช้ไฟล์แบรนด์จริง สลับอัตโนมัติตามธีม
 *   assets/img/logo-light.png  → ใช้บนพื้นสว่าง
 *   assets/img/logo-dark.png   → ใช้บนพื้นมืด
 * (ทั้งสองไฟล์ครอปขอบโปร่งใสออกแล้ว จึงตั้งความสูงค่าเดียวได้พอดีทั้งคู่)
 */
?>
<span class="logo">
    <img class="logo__img logo__img--light" src="<?= e(asset('assets/img/logo-light.png')) ?>"
         alt="TEXSON" width="414" height="102" decoding="async">
    <img class="logo__img logo__img--dark" src="<?= e(asset('assets/img/logo-dark.png')) ?>"
         alt="TEXSON" width="408" height="101" decoding="async">
</span>
