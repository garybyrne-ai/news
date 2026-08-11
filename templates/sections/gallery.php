<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$items = array_values(array_filter($content['items'] ?? [], fn ($g) => trim($g['src'] ?? '') !== ''));
if (!$items) {
    return;
}
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container">
    <header class="section-head reveal" data-reveal>
      <p class="eyebrow"><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="section-title"><?= e($content['heading'] ?? '') ?></h2>
      <?php if (!empty($content['text'])): ?>
      <p class="gallery__text"><?= e($content['text']) ?></p>
      <?php endif; ?>
    </header>

    <div class="gallery">
      <?php foreach ($items as $i => $item): ?>
      <figure class="gallery__item reveal" data-reveal data-reveal-delay="<?= ($i % 5) * 60 ?>">
        <img src="<?= e(media_url($item['src'])) ?>" alt="<?= e($item['alt'] ?? $item['caption'] ?? '') ?>"<?= img_size_attrs($item['src']) ?>
             loading="<?= $i < 2 ? 'eager' : 'lazy' ?>" decoding="async">
        <?php if (!empty($item['caption'])): ?>
        <figcaption class="gallery__caption"><?= e($item['caption']) ?></figcaption>
        <?php endif; ?>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
