<?php declare(strict_types=1); ?>
<section class="section section--alt" id="products">
    <div class="container">
        <header class="section__head reveal">
            <h2 class="section__title"><?= te('products.title') ?></h2>
            <p class="section__subtitle"><?= te('products.subtitle') ?></p>
        </header>

        <div class="grid grid--4">
            <?php foreach ((array)t('products.items', []) as $i => $item): ?>
                <article class="card card--product reveal" style="--delay: <?= $i * 70 ?>ms">
                    <span class="card__icon" aria-hidden="true"><?= e((string)$item['icon']) ?></span>
                    <h3 class="card__title"><?= e((string)$item['title']) ?></h3>
                    <ul class="arrowlist">
                        <?php foreach ((array)$item['list'] as $line): ?>
                            <li><?= e((string)$line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
