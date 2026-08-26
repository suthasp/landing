<?php declare(strict_types=1); ?>
<section class="hero" id="hero">
    <div class="hero__glow" aria-hidden="true"></div>
    <div class="container hero__inner">
        <div class="hero__content reveal">
            <span class="badge"><?= te('hero.badge') ?></span>

            <h1 class="hero__title">
                <?= te('hero.title_1') ?><br>
                <?= te('hero.title_2') ?><span class="mark"><?= te('hero.title_mark') ?></span>
            </h1>

            <p class="hero__lead"><?= te('hero.lead') ?></p>

            <div class="hero__actions">
                <a class="btn btn--primary btn--lg" href="#contact"><?= te('hero.cta_1') ?></a>
                <a class="btn btn--ghost btn--lg" href="#services"><?= te('hero.cta_2') ?></a>
            </div>

            <dl class="stats">
                <?php foreach ((array)t('stats', []) as $stat): ?>
                    <div class="stats__item">
                        <dt class="stats__value"><?= e((string)$stat['value']) ?></dt>
                        <dd class="stats__label"><?= e((string)$stat['label']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>

        <div class="hero__visual reveal" aria-hidden="true">
            <div class="rack">
                <div class="rack__head">
                    <span class="rack__dot rack__dot--live"></span>
                    <span class="rack__title">SERVER ROOM · STATUS</span>
                </div>
                <ul class="rack__rows">
                    <li><span>UPS &amp; Battery</span><b class="pill pill--ok">ONLINE</b></li>
                    <li><span>Generator / ATS</span><b class="pill pill--ok">READY</b></li>
                    <li><span>Precision Air</span><b class="pill pill--ok">22.4&deg;C</b></li>
                    <li><span>PM Schedule</span><b class="pill pill--warn">DUE 7D</b></li>
                    <li><span>Fire Suppression</span><b class="pill pill--ok">NORMAL</b></li>
                    <li><span>Access / CCTV</span><b class="pill pill--ok">ARMED</b></li>
                </ul>
                <div class="rack__foot">
                    <span class="rack__bar"><i style="--w:86%"></i></span>
                    <span class="rack__bar"><i style="--w:64%"></i></span>
                    <span class="rack__bar"><i style="--w:93%"></i></span>
                </div>
            </div>
        </div>
    </div>
</section>
