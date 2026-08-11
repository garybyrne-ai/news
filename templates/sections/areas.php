<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$places = $content['places'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container areas__layout">
    <div class="areas__copy">
      <p class="eyebrow reveal" data-reveal><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="display-title reveal" data-reveal data-reveal-delay="60"><?= e($content['heading'] ?? '') ?></h2>
      <p class="areas__text reveal" data-reveal data-reveal-delay="120"><?= e($content['text'] ?? '') ?></p>
      <?php if (!empty($content['cta'])): ?>
      <a class="btn btn--ghost reveal" data-reveal data-reveal-delay="180" href="<?= e($content['cta_url'] ?? '#contact') ?>"><?= e($content['cta']) ?> <span aria-hidden="true">→</span></a>
      <?php endif; ?>
    </div>

    <div class="areas__radar reveal" data-reveal data-reveal-delay="120" role="img"
         aria-label="Distances from the villa: <?php $bits = []; foreach ($places as $p) { $bits[] = ($p['name'] ?? '') . ' ' . ($p['distance'] ?? ''); } echo e(implode(', ', $bits)); ?>">
      <div class="areas__rings" aria-hidden="true">
        <span></span><span></span><span></span>
        <span class="areas__pin"><span class="status-dot status-dot--pulse"></span> Villa Andie</span>
      </div>
      <ul class="areas__list" aria-hidden="true">
        <?php foreach ($places as $i => $place): ?>
        <li class="areas__place" style="--i:<?= $i ?>">
          <span class="areas__name"><?= e($place['name'] ?? '') ?></span>
          <span class="areas__dots"></span>
          <span class="areas__distance"><?= e($place['distance'] ?? '') ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
