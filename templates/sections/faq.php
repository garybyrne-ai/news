<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$items      = $content['items'] ?? [];
$singleOpen = setting('faq_single_open', '1') === '1';
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container faq__layout">
    <div class="faq__head">
      <p class="eyebrow reveal" data-reveal><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="section-title reveal" data-reveal data-reveal-delay="60"><?= e($content['heading'] ?? '') ?></h2>
      <p class="faq__text reveal" data-reveal data-reveal-delay="120"><?= e($content['text'] ?? '') ?></p>
      <?php if (!empty($content['cta'])): ?>
      <a class="btn btn--ghost reveal" data-reveal data-reveal-delay="180" href="<?= e($content['cta_url'] ?? '#contact') ?>"><?= e($content['cta']) ?> <span aria-hidden="true">→</span></a>
      <?php endif; ?>
    </div>

    <div class="accordion" data-accordion data-single-open="<?= $singleOpen ? '1' : '0' ?>">
      <?php foreach ($items as $i => $item): ?>
      <div class="accordion__item reveal" data-reveal data-reveal-delay="<?= $i * 50 ?>">
        <h3 class="accordion__heading">
          <button class="accordion__trigger" id="faq-t-<?= $i ?>" aria-expanded="false" aria-controls="faq-p-<?= $i ?>">
            <span><?= e($item['q'] ?? '') ?></span>
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
        </h3>
        <div class="accordion__panel" id="faq-p-<?= $i ?>" role="region" aria-labelledby="faq-t-<?= $i ?>" hidden>
          <p><?= e($item['a'] ?? '') ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
