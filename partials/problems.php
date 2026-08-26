<?php declare(strict_types=1); ?>
<section class="section section--alt" id="problems">
    <div class="container">
        <header class="section__head reveal">
            <h2 class="section__title"><?= te('problems.title') ?></h2>
            <p class="section__subtitle"><?= te('problems.subtitle') ?></p>
        </header>

        <div class="grid grid--4">
            <?php foreach ((array)t('problems.items', []) as $i => $item): ?>
                <article class="card card--problem reveal" style="--delay: <?= $i * 70 ?>ms">
                    <span class="card__icon" aria-hidden="true"><?= e((string)$item['icon']) ?></span>
                    <h3 class="card__title"><?= e((string)$item['title']) ?></h3>
                    <p class="card__text"><?= e((string)$item['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
