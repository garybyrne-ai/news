<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$metrics = $content['metrics'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container">
    <h2 class="statement reveal" data-reveal data-split-words><?= e($content['statement'] ?? '') ?></h2>
    <?php if (!empty($content['text'])): ?>
    <p class="intro__text reveal" data-reveal data-reveal-delay="120"><?= e($content['text']) ?></p>
    <?php endif; ?>
    <?php $strip = array_values(array_filter($content['images'] ?? [], fn ($im) => trim($im['src'] ?? '') !== '')); ?>
    <?php if ($strip): ?>
    <div class="intro__strip">
      <?php foreach ($strip as $i => $im): ?>
      <figure class="intro__photo reveal" data-reveal data-reveal-delay="<?= 120 + $i * 100 ?>">
        <img src="<?= e(media_url($im['src'])) ?>" alt="<?= e($im['alt'] ?? '') ?>"<?= img_size_attrs($im['src']) ?> loading="lazy" decoding="async">
      </figure>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($metrics): ?>
    <dl class="metrics">
      <?php foreach ($metrics as $i => $metric): ?>
      <div class="metric reveal" data-reveal data-reveal-delay="<?= $i * 90 ?>">
        <dd class="metric__value"><?= e($metric['value'] ?? '') ?></dd>
        <dt class="metric__label"><?= e($metric['label'] ?? '') ?></dt>
      </div>
      <?php endforeach; ?>
    </dl>
    <?php endif; ?>
  </div>
</section>
