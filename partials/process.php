<?php declare(strict_types=1); ?>
<section class="section section--alt" id="process">
    <div class="container">
        <header class="section__head reveal">
            <h2 class="section__title"><?= te('process.title') ?></h2>
            <p class="section__subtitle"><?= te('process.subtitle') ?></p>
        </header>

        <ol class="steps">
            <?php foreach ((array)t('process.items', []) as $i => $step): ?>
                <li class="step reveal" style="--delay: <?= $i * 80 ?>ms">
                    <span class="step__no"><?= $i + 1 ?></span>
                    <div class="step__body">
                        <h3 class="step__title"><?= e((string)$step['title']) ?></h3>
                        <p class="step__text"><?= e((string)$step['text']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
