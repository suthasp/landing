<?php declare(strict_types=1); ?>
<header class="site-header">
    <div class="container site-header__inner">
        <a class="site-header__brand" href="<?= e(url_with([])) ?>#top">
            <?php section('logo'); ?>
        </a>

        <nav class="nav" id="primary-nav" aria-label="<?= te('nav.menu') ?>">
            <a href="#services"><?= te('nav.services') ?></a>
            <a href="#products"><?= te('nav.products') ?></a>
            <a href="#why"><?= te('nav.why') ?></a>
            <a href="#process"><?= te('nav.process') ?></a>
            <a href="#contact"><?= te('nav.contact') ?></a>
            <a class="btn btn--primary nav__cta" href="#contact"><?= te('nav.quote') ?></a>
        </nav>

        <div class="site-header__tools">
            <a class="icon-btn" href="<?= e(url_with(['lang' => other_lang()])) ?>"
               title="<?= te('nav.lang') ?>" aria-label="<?= te('nav.lang') ?>">
                <?= strtoupper(other_lang()) ?>
            </a>

            <a class="icon-btn" id="theme-toggle" role="button"
               href="<?= e(url_with(['theme' => current_theme() === 'dark' ? 'light' : 'dark'])) ?>"
               title="<?= te('nav.theme') ?>" aria-label="<?= te('nav.theme') ?>">
                <svg class="icon icon--sun" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="4.2" fill="none" stroke-width="1.8"/>
                    <path d="M12 2.4v2.6M12 19v2.6M2.4 12h2.6M19 12h2.6M5.2 5.2l1.9 1.9M16.9 16.9l1.9 1.9M18.8 5.2l-1.9 1.9M7.1 16.9l-1.9 1.9"
                          fill="none" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <svg class="icon icon--moon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 14.2A8.4 8.4 0 0 1 9.8 4 8.4 8.4 0 1 0 20 14.2Z" fill="none" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
            </a>

            <a class="btn btn--primary site-header__quote" href="#contact"><?= te('nav.quote') ?></a>

            <button class="burger" id="nav-toggle" type="button"
                    aria-expanded="false" aria-controls="primary-nav" aria-label="<?= te('nav.menu') ?>">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
