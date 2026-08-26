<?php declare(strict_types=1); ?>
<section class="section" id="services">
    <div class="container">
        <header class="section__head reveal">
            <h2 class="section__title"><?= te('services.title') ?></h2>
            <p class="section__subtitle"><?= te('services.subtitle') ?></p>
        </header>

        <div class="grid grid--3">
            <?php foreach ((array)t('services.items', []) as $i => $service): ?>
                <article class="card card--service reveal" style="--delay: <?= $i * 90 ?>ms">
                    <span class="card__eyebrow"><?= e((string)$service['no']) ?></span>
                    <h3 class="card__title card__title--lg"><?= e((string)$service['title']) ?></h3>

                    <ul class="checklist">
                        <?php foreach ((array)$service['points'] as $point): ?>
                            <li><?= e((string)$point) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <p class="card__note">
                        <strong><?= te('services.best_for') ?></strong> <?= e((string)$service['note']) ?>
                    </p>

                    <a class="card__link" href="#contact">
                        <?= current_lang() === 'th' ? 'สอบถามบริการนี้' : 'Ask about this service' ?>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
