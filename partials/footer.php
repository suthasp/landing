<?php declare(strict_types=1); ?>
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand">
            <?php section('logo'); ?>
            <p class="site-footer__tagline"><?= te('footer.tagline') ?></p>
        </div>

        <nav class="site-footer__nav" aria-label="<?= te('footer.nav_title') ?>">
            <h3 class="site-footer__title"><?= te('footer.nav_title') ?></h3>
            <a href="#services"><?= te('nav.services') ?></a>
            <a href="#products"><?= te('nav.products') ?></a>
            <a href="#why"><?= te('nav.why') ?></a>
            <a href="#process"><?= te('nav.process') ?></a>
            <a href="#contact"><?= te('nav.contact') ?></a>
        </nav>

        <div class="site-footer__contact">
            <h3 class="site-footer__title"><?= te('footer.contact_title') ?></h3>
            <a href="tel:<?= e((string)cfg('contact.phone_tel')) ?>"><?= e((string)cfg('contact.phone')) ?></a>
            <a href="mailto:<?= e((string)cfg('contact.email')) ?>"><?= e((string)cfg('contact.email')) ?></a>
            <span><?= te('contact.hours') ?></span>
            <?php if (cfg('webmail_url')): ?>
                <a href="<?= e((string)cfg('webmail_url')) ?>" target="_blank" rel="noopener"><?= te('nav.webmail') ?> &nearr;</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container site-footer__bottom">
        <p><?= e(sprintf((string)t('footer.copyright', '© %s Texson'), date('Y'))) ?></p>
        <a class="site-footer__top" href="#top"><?= te('footer.back_to_top') ?> <span aria-hidden="true">&uarr;</span></a>
    </div>
</footer>
