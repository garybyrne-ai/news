<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$cards = $content['cards'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="hero__bg" aria-hidden="true">
    <div class="hero__glow hero__glow--a"></div>
    <div class="hero__glow hero__glow--b"></div>
    <div class="hero__grid"></div>
  </div>

  <div class="container hero__layout">
    <div class="hero__copy">
      <p class="eyebrow reveal" data-reveal>
        <span class="status-dot" aria-hidden="true"></span>
        <?= e($content['eyebrow'] ?? '') ?>
      </p>
      <h1 class="hero__title reveal" data-reveal data-reveal-delay="80">
        <?php foreach (explode("\n", $content['headline'] ?? '') as $line): ?>
        <span class="hero__line"><span><?= e(trim($line)) ?></span></span>
        <?php endforeach; ?>
      </h1>
      <p class="hero__intro reveal" data-reveal data-reveal-delay="160"><?= e($content['intro'] ?? '') ?></p>
      <div class="hero__actions reveal" data-reveal data-reveal-delay="240">
        <a class="btn btn--primary btn--lg" data-magnetic href="<?= e($content['cta_primary_url'] ?? '#contact') ?>"><?= e($content['cta_primary'] ?? 'Contact us') ?></a>
        <a class="btn btn--ghost btn--lg" data-cursor="explore" href="<?= e($content['cta_secondary_url'] ?? '#about') ?>"><?= e($content['cta_secondary'] ?? 'Explore') ?> <span aria-hidden="true">↓</span></a>
      </div>
      <p class="hero__status reveal" data-reveal data-reveal-delay="320">
        <span class="status-dot status-dot--pulse" aria-hidden="true"></span>
        <?= e(setting('status_text', 'Enquiries open')) ?> · <?= e(setting('locality', 'Puerto del Carmen')) ?>
      </p>
    </div>

    <div class="hero__visual" data-parallax-scene aria-hidden="true">
      <svg class="hero__scene" viewBox="0 0 520 560" fill="none" role="presentation" data-parallax-layer="6">
        <defs>
          <linearGradient id="hg-sky" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="var(--color-secondary)" stop-opacity=".55"/>
            <stop offset="1" stop-color="var(--color-secondary)" stop-opacity="0"/>
          </linearGradient>
          <linearGradient id="hg-sea" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="var(--color-primary)"/>
            <stop offset="1" stop-color="var(--color-primary)" stop-opacity=".25"/>
          </linearGradient>
        </defs>
        <circle class="hero__sun" cx="360" cy="150" r="86" fill="var(--color-accent)"/>
        <path d="M0 320 L150 230 L240 300 L360 240 L520 330 L520 560 L0 560 Z" fill="#1c1915"/>
        <path d="M0 360 L120 290 L230 350 L390 280 L520 370 L520 560 L0 560 Z" fill="#242019"/>
        <rect x="0" y="150" width="520" height="180" fill="url(#hg-sky)" opacity=".35"/>
        <g class="hero__waves">
          <path d="M0 420 Q130 405 260 420 T520 420 L520 560 L0 560 Z" fill="url(#hg-sea)" opacity=".9"/>
          <path d="M0 455 Q130 442 260 455 T520 455" stroke="var(--color-secondary)" stroke-opacity=".5" stroke-width="2"/>
          <path d="M0 490 Q130 478 260 490 T520 490" stroke="var(--color-secondary)" stroke-opacity=".3" stroke-width="2"/>
          <path d="M0 525 Q130 514 260 525 T520 525" stroke="var(--color-secondary)" stroke-opacity=".15" stroke-width="2"/>
        </g>
      </svg>
      <?php foreach (array_slice($cards, 0, 3) as $i => $card): ?>
      <div class="hero-card hero-card--<?= $i + 1 ?>" data-parallax-layer="<?= 10 + $i * 6 ?>">
        <span class="hero-card__title"><?= e($card['title'] ?? '') ?></span>
        <span class="hero-card__text"><?= e($card['text'] ?? '') ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <a class="scroll-cue" href="#about" data-scroll-next data-cursor="down">
    <span><?= e($content['scroll_cue'] ?? 'Scroll to explore') ?></span>
    <span class="scroll-cue__line" aria-hidden="true"></span>
  </a>
</section>
