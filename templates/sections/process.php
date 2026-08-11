<?php
/** @var array $section @var array $content @var string $anchor @var int $index */
$steps = $content['steps'] ?? [];
?>
<section <?= section_attrs($section, $anchor, $index) ?>>
  <div class="container">
    <header class="section-head reveal" data-reveal>
      <p class="eyebrow"><?= e($content['eyebrow'] ?? '') ?></p>
      <h2 class="section-title"><?= e($content['heading'] ?? '') ?></h2>
    </header>

    <ol class="process" data-process>
      <span class="process__track" aria-hidden="true"><span class="process__progress" data-process-progress></span></span>
      <?php foreach ($steps as $i => $step): ?>
      <li class="process__step reveal" data-reveal data-reveal-delay="<?= $i * 110 ?>">
        <span class="process__dot" aria-hidden="true"></span>
        <span class="process__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
        <h3 class="process__title"><?= e($step['title'] ?? '') ?></h3>
        <p class="process__text"><?= e($step['text'] ?? '') ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
