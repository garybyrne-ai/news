<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$features = $content['features'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container split__layout">
    <figure class="split__media reveal" data-reveal data-clip>
      <span class="hud-corner hud-corner--tl" aria-hidden="true"></span>
      <span class="hud-corner hud-corner--br" aria-hidden="true"></span>
      <?php if (!empty($content['image_label'])): ?>
      <figcaption class="split__label" aria-hidden="true"><?= e($content['image_label']) ?></figcaption>
      <?php endif; ?>
      <?php if (!empty($content['image'])): ?>
      <img src="<?= e(media_url($content['image'])) ?>" alt="<?= e($content['heading'] ?? '') ?>" width="880" height="1100" loading="lazy" decoding="async" data-parallax-img>
      <?php else: ?>
      <div class="split__placeholder" aria-hidden="true" data-parallax-img>
        <svg viewBox="0 0 480 600" fill="none" role="presentation" preserveAspectRatio="xMidYMid slice">
          <rect width="480" height="600" fill="var(--color-primary)" opacity=".12"/>
          <circle cx="360" cy="140" r="70" fill="var(--color-accent)" opacity=".85"/>
          <path d="M0 380 L140 300 L260 370 L400 290 L480 350 L480 600 L0 600 Z" fill="var(--color-primary)" opacity=".35"/>
          <path d="M0 440 Q120 425 240 440 T480 440 L480 600 L0 600 Z" fill="var(--color-primary)" opacity=".55"/>
          <path d="M0 500 Q120 488 240 500 T480 500" stroke="var(--color-surface)" stroke-opacity=".6" stroke-width="2"/>
        </svg>
      </div>
      <?php endif; ?>
    </figure>
    <div class="split__content">
      <p class="eyebrow reveal" data-reveal><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="section-title reveal" data-reveal data-reveal-delay="60"><?= e($content['heading'] ?? '') ?></h2>
      <p class="split__text reveal" data-reveal data-reveal-delay="120"><?= e($content['text'] ?? '') ?></p>
      <?php if ($features): ?>
      <ul class="feature-list">
        <?php foreach ($features as $i => $feature): ?>
        <li class="reveal" data-reveal data-reveal-delay="<?= 150 + $i * 60 ?>"><span class="feature-list__tick" aria-hidden="true">✓</span> <?= e($feature) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <?php if (!empty($content['cta'])): ?>
      <a class="btn btn--primary reveal" data-reveal data-reveal-delay="300" data-magnetic href="<?= e($content['cta_url'] ?? '#contact') ?>"><?= e($content['cta']) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
