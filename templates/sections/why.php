<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$items = $content['items'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container">
    <h2 class="display-title reveal" data-reveal><?= e($content['heading'] ?? '') ?></h2>
    <ol class="why-list">
      <?php foreach ($items as $i => $item): ?>
      <li class="why-item reveal" data-reveal data-reveal-delay="<?= $i * 90 ?>">
        <span class="why-item__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
        <div class="why-item__body">
          <h3 class="why-item__title"><?= e($item['title'] ?? '') ?></h3>
          <p class="why-item__text"><?= e($item['text'] ?? '') ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
