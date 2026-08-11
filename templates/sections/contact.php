<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$topics  = $content['topics'] ?? [];
$phone   = setting('phone');
$email   = setting('email');
$address = setting('address');
$hours   = setting('hours');
$success = flash_get('contact_success');
$error   = flash_get('contact_error');
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container contact__layout">
    <div class="contact__info">
      <p class="eyebrow reveal" data-reveal><?= e($content['eyebrow'] ?? 'Contact') ?></p>
      <h2 class="section-title reveal" data-reveal data-reveal-delay="60"><?= e($content['heading'] ?? '') ?></h2>
      <p class="contact__text reveal" data-reveal data-reveal-delay="120"><?= e($content['text'] ?? '') ?></p>

      <?php if (!empty($content['image'])): ?>
      <figure class="contact__photo reveal" data-reveal data-reveal-delay="160">
        <img src="<?= e(media_url($content['image'])) ?>" alt=""<?= img_size_attrs($content['image']) ?> loading="lazy" decoding="async">
      </figure>
      <?php endif; ?>

      <dl class="contact__details reveal" data-reveal data-reveal-delay="180">
        <?php if ($phone !== ''): ?>
        <div><dt>Phone</dt><dd><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a></dd></div>
        <?php endif; ?>
        <?php if ($email !== ''): ?>
        <div><dt>Email</dt><dd><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></dd></div>
        <?php endif; ?>
        <?php if ($address !== ''): ?>
        <div><dt>Location</dt><dd><?= e($address) ?></dd></div>
        <?php endif; ?>
        <?php if ($hours !== ''): ?>
        <div><dt>Hours</dt><dd><?= e($hours) ?></dd></div>
        <?php endif; ?>
      </dl>

      <?php $ig = setting('instagram'); $fb = setting('facebook'); ?>
      <?php if ($ig !== '' || $fb !== ''): ?>
      <p class="contact__social reveal" data-reveal data-reveal-delay="240">
        <?php if ($ig !== ''): ?><a href="<?= e($ig) ?>" rel="noopener">Instagram</a><?php endif; ?>
        <?php if ($fb !== ''): ?><a href="<?= e($fb) ?>" rel="noopener">Facebook</a><?php endif; ?>
      </p>
      <?php endif; ?>
    </div>

    <form class="contact-form reveal" data-reveal data-reveal-delay="120" method="post"
          action="<?= e(url('/enquiry')) ?>" data-contact-form novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="_started" value="<?= time() ?>">
      <p class="visually-hidden" aria-hidden="true">
        <label>Leave this field empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </p>

      <div class="form-status" data-form-status role="status" aria-live="polite">
        <?php if ($success): ?><p class="form-status__ok"><?= e($success) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="form-status__err"><?= e($error) ?></p><?php endif; ?>
      </div>

      <div class="form-grid">
        <div class="field">
          <label for="f-name">Name</label>
          <input id="f-name" name="name" type="text" required autocomplete="name" maxlength="120">
        </div>
        <div class="field">
          <label for="f-email">Email</label>
          <input id="f-email" name="email" type="email" required autocomplete="email" maxlength="200">
        </div>
        <div class="field">
          <label for="f-phone">Phone <span class="field__optional">(optional)</span></label>
          <input id="f-phone" name="phone" type="tel" autocomplete="tel" maxlength="40">
        </div>
        <div class="field">
          <label for="f-topic">Enquiry about</label>
          <select id="f-topic" name="topic">
            <?php foreach ($topics as $topic): ?>
            <option><?= e($topic) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field field--full">
          <label for="f-message">Message</label>
          <textarea id="f-message" name="message" rows="5" required maxlength="5000"
                    placeholder="Your dates, party size, and anything you'd like to ask."></textarea>
        </div>
        <div class="field field--full field--check">
          <input id="f-consent" name="consent" type="checkbox" required>
          <label for="f-consent">I'm happy for <?= e(setting('business_name')) ?> to reply to my enquiry using the details above.</label>
        </div>
      </div>

      <button class="btn btn--primary btn--lg contact-form__submit" data-magnetic type="submit">
        <span data-submit-label>Send enquiry</span>
      </button>
    </form>
  </div>
</section>
