<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$phone = setting('phone');
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="cta__bg" aria-hidden="true"<?php if (!empty($content['image'])): ?> style="--cta-photo:url('<?= e(media_url($content['image'])) ?>')"<?php endif; ?>></div>
  <div class="container cta__inner">
    <h2 class="cta__title reveal" data-reveal data-split-words><?= e($content['heading'] ?? 'Ready to get started?') ?></h2>
    <p class="cta__text reveal" data-reveal data-reveal-delay="100"><?= e($content['text'] ?? '') ?></p>
    <div class="cta__actions reveal" data-reveal data-reveal-delay="180">
      <a class="btn btn--light btn--lg" data-magnetic href="<?= e($content['cta_primary_url'] ?? '#contact') ?>"><?= e($content['cta_primary'] ?? 'Contact us') ?></a>
      <?php if ($phone !== '' && !empty($content['cta_secondary'])): ?>
      <a class="btn btn--outline-light btn--lg" href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($content['cta_secondary']) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
