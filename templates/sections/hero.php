<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$cards = $content['cards'] ?? [];
$image = media_url($content['image'] ?? '');

// Ticker items derive from live CMS content: amenity titles + location facts.
$tickerItems = [];
if ($services = section_by_type('services')) {
    foreach (section_content($services)['items'] ?? [] as $item) {
        if (!empty($item['title'])) {
            $tickerItems[] = $item['title'];
        }
    }
}
$tickerItems[] = setting('locality', 'Puerto del Carmen');
$tickerItems[] = '1.3 km to the beach';
$tickerItems[] = '6 km from the airport';
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

    <div class="hero__visual" data-parallax-scene>
      <figure class="hero__frame" data-parallax-layer="5">
        <?php if ($image !== ''): ?>
        <img class="hero__photo" src="<?= e($image) ?>" width="1100" height="733"
             alt="<?= e($content['image_alt'] ?? '') ?>" fetchpriority="high" decoding="async">
        <?php endif; ?>
        <span class="hud-corner hud-corner--tl" aria-hidden="true"></span>
        <span class="hud-corner hud-corner--tr" aria-hidden="true"></span>
        <span class="hud-corner hud-corner--bl" aria-hidden="true"></span>
        <span class="hud-corner hud-corner--br" aria-hidden="true"></span>
        <span class="hero__scan" aria-hidden="true"></span>
        <figcaption class="hero__coords" aria-hidden="true">
          <span class="hero__coords-dot status-dot status-dot--pulse"></span>
          <?= e(setting('geo_label')) ?> — <?= e(strtoupper(setting('locality', ''))) ?>
        </figcaption>
      </figure>
      <?php foreach (array_slice($cards, 0, 3) as $i => $card): ?>
      <div class="hero-card hero-card--<?= $i + 1 ?>" data-parallax-layer="<?= 12 + $i * 7 ?>">
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

  <?php if ($tickerItems): ?>
  <div class="ticker" aria-hidden="true">
    <div class="ticker__track">
      <?php for ($r = 0; $r < 2; $r++): ?>
      <span class="ticker__group">
        <?php foreach ($tickerItems as $item): ?>
        <span class="ticker__item"><?= e($item) ?></span><span class="ticker__sep">◇</span>
        <?php endforeach; ?>
      </span>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>
</section>
