<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$items = $content['items'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container">
    <header class="section-head reveal" data-reveal>
      <p class="eyebrow"><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="section-title"><?= e($content['heading'] ?? '') ?></h2>
    </header>

    <ul class="service-list" data-service-list>
      <?php foreach ($items as $i => $item): ?>
      <li class="service-row reveal" data-reveal data-reveal-delay="<?= $i * 60 ?>" data-visual="<?= e($item['visual'] ?? '') ?>">
        <a class="service-row__link" href="#contact" data-cursor="enquire" aria-label="<?= e(($item['title'] ?? '') . ' — enquire') ?>">
          <span class="service-row__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <span class="service-row__body">
            <span class="service-row__title"><?= e($item['title'] ?? '') ?></span>
            <span class="service-row__text"><?= e($item['text'] ?? '') ?></span>
          </span>
          <span class="service-row__arrow" aria-hidden="true">→</span>
        </a>
        <?php if (!empty($item['image'])): ?>
        <span class="service-row__thumb" aria-hidden="true"><img src="<?= e(media_url($item['image'])) ?>" alt=""<?= img_size_attrs($item['image']) ?> loading="lazy" decoding="async"></span>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
