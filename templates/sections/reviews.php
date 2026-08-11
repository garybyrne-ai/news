<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$items = array_values(array_filter($content['items'] ?? [], fn ($r) => trim($r['quote'] ?? '') !== ''));
if (!$items) {
    return;
}
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container">
    <header class="section-head reveal" data-reveal>
      <p class="eyebrow"><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="section-title"><?= e($content['heading'] ?? '') ?></h2>
    </header>

    <div class="reviews" data-reviews>
      <div class="reviews__viewport">
        <?php foreach ($items as $i => $review): ?>
        <figure class="review<?= $i === 0 ? ' is-active' : '' ?>" data-review="<?= $i ?>" <?= $i === 0 ? '' : 'aria-hidden="true"' ?>>
          <blockquote class="review__quote">“<?= e($review['quote']) ?>”</blockquote>
          <figcaption class="review__meta">
            <span class="review__name">— <?= e($review['name'] ?? '') ?></span>
            <?php if (!empty($review['location'])): ?><span class="review__location"><?= e($review['location']) ?></span><?php endif; ?>
            <?php if (!empty($review['rating'])): ?><span class="review__rating" aria-label="Rated <?= e($review['rating']) ?> out of 5"><?= e($review['rating']) ?> / 5</span><?php endif; ?>
            <?php if (!empty($review['source'])): ?><span class="review__source">via <?= e($review['source']) ?></span><?php endif; ?>
          </figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
      <?php if (count($items) > 1): ?>
      <div class="reviews__nav">
        <button class="reviews__btn" data-review-prev aria-label="Previous review">←</button>
        <span class="reviews__count"><span data-review-current>1</span> / <?= count($items) ?></span>
        <button class="reviews__btn" data-review-next aria-label="Next review">→</button>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
