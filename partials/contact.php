<?php
declare(strict_types=1);

$status   = $GLOBALS['FORM_STATUS'] ?? null;
$messages = (array)t('contact.messages', []);
$options  = (array)t('contact.form.service_options', []);
$formErr  = form_error('form');
?>
<section class="section" id="contact">
    <div class="container contact">
        <div class="contact__info reveal">
            <h2 class="section__title section__title--left"><?= te('contact.title') ?></h2>
            <p class="section__subtitle section__subtitle--left"><?= te('contact.subtitle') ?></p>

            <ul class="contact__list">
                <li>
                    <span class="contact__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M6.6 3.5h3l1.5 3.7-2 1.4a11 11 0 0 0 5.3 5.3l1.4-2 3.7 1.5v3a2 2 0 0 1-2.2 2A15.6 15.6 0 0 1 4.6 5.7a2 2 0 0 1 2-2.2Z" fill="none" stroke-width="1.7" stroke-linejoin="round"/></svg>
                    </span>
                    <span class="contact__meta">
                        <span class="contact__label"><?= te('contact.phone_label') ?></span>
                        <a class="contact__value" href="tel:<?= e((string)cfg('contact.phone_tel')) ?>"><?= e((string)cfg('contact.phone')) ?></a>
                    </span>
                </li>
                <li>
                    <span class="contact__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2.5" fill="none" stroke-width="1.7"/><path d="m3.8 6.6 8.2 6 8.2-6" fill="none" stroke-width="1.7" stroke-linecap="round"/></svg>
                    </span>
                    <span class="contact__meta">
                        <span class="contact__label"><?= te('contact.email_label') ?></span>
                        <a class="contact__value" href="mailto:<?= e((string)cfg('contact.email')) ?>"><?= e((string)cfg('contact.email')) ?></a>
                    </span>
                </li>
                <li>
                    <span class="contact__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.6" fill="none" stroke-width="1.7"/><path d="M12 7.2V12l3.2 2" fill="none" stroke-width="1.7" stroke-linecap="round"/></svg>
                    </span>
                    <span class="contact__meta">
                        <span class="contact__label"><?= te('contact.hours_label') ?></span>
                        <span class="contact__value contact__value--plain"><?= te('contact.hours') ?></span>
                    </span>
                </li>
            </ul>
        </div>

        <div class="contact__form-wrap reveal">
            <?php if ($status === 'success'): ?>
                <p class="alert alert--success" role="status"><?= e((string)($messages['success'] ?? '')) ?></p>
            <?php elseif ($status === 'error'): ?>
                <p class="alert alert--error" role="alert">
                    <?= e((string)($formErr ?? $messages['error'] ?? '')) ?>
                </p>
            <?php endif; ?>

            <form class="form" method="post" action="<?= e(url_with([])) ?>#contact" novalidate>
                <input type="hidden" name="form" value="contact">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <div class="form__hp" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="field">
                    <label class="field__label" for="f-name">
                        <?= te('contact.form.name') ?> <span class="req">*</span>
                    </label>
                    <input class="field__input<?= form_error('name') ? ' is-invalid' : '' ?>" id="f-name" name="name" type="text"
                           required maxlength="120" autocomplete="name"
                           placeholder="<?= te('contact.form.name_ph') ?>" value="<?= old('name') ?>">
                    <?php if ($err = form_error('name')): ?>
                        <span class="field__error"><?= e($err) ?></span>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label class="field__label" for="f-company"><?= te('contact.form.company') ?></label>
                    <input class="field__input" id="f-company" name="company" type="text" maxlength="120"
                           autocomplete="organization"
                           placeholder="<?= te('contact.form.company_ph') ?>" value="<?= old('company') ?>">
                </div>

                <div class="field">
                    <label class="field__label" for="f-contact">
                        <?= te('contact.form.contact') ?> <span class="req">*</span>
                    </label>
                    <input class="field__input<?= form_error('contact') ? ' is-invalid' : '' ?>" id="f-contact" name="contact" type="text"
                           required maxlength="160"
                           placeholder="<?= te('contact.form.contact_ph') ?>" value="<?= old('contact') ?>">
                    <?php if ($err = form_error('contact')): ?>
                        <span class="field__error"><?= e($err) ?></span>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label class="field__label" for="f-service"><?= te('contact.form.service') ?></label>
                    <div class="field__select">
                        <select class="field__input" id="f-service" name="service">
                            <?php foreach ($options as $value => $label): ?>
                                <option value="<?= e((string)$value) ?>" <?= old('service') === $value ? 'selected' : '' ?>>
                                    <?= e((string)$label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="field__label" for="f-details"><?= te('contact.form.details') ?></label>
                    <textarea class="field__input field__input--area<?= form_error('details') ? ' is-invalid' : '' ?>"
                              id="f-details" name="details" rows="4" maxlength="2000"
                              placeholder="<?= te('contact.form.details_ph') ?>"><?= old('details') ?></textarea>
                    <?php if ($err = form_error('details')): ?>
                        <span class="field__error"><?= e($err) ?></span>
                    <?php endif; ?>
                </div>

                <button class="btn btn--primary btn--block" type="submit"
                        data-sending="<?= te('contact.form.sending') ?>">
                    <?= te('contact.form.submit') ?>
                </button>
            </form>
        </div>
    </div>
</section>
