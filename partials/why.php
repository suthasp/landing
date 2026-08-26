<?php declare(strict_types=1); ?>
<section class="section" id="why">
    <div class="container">
        <header class="section__head reveal">
            <h2 class="section__title"><?= te('why.title') ?></h2>
            <p class="section__subtitle"><?= te('why.subtitle') ?></p>
        </header>

        <div class="grid grid--4">
            <?php foreach ((array)t('why.items', []) as $i => $item): ?>
                <article class="reason reveal" style="--delay: <?= $i * 70 ?>ms">
                    <h3 class="reason__title"><?= e((string)$item['title']) ?></h3>
                    <p class="reason__text"><?= e((string)$item['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
