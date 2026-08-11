<?php
/** @var array[] $sections */
/** @var bool $preview */

$businessName = setting('business_name', 'Villa Andie');
$navSections  = array_values(array_filter($sections, fn ($s) => !in_array($s['type'], ['cta'], true)));
$navItems     = [];
foreach ($sections as $s) {
    if (in_array($s['type'], ['intro', 'services', 'why', 'process', 'areas', 'reviews', 'faq', 'contact'], true)) {
        $navItems[] = [
            'anchor' => $s['anchor'] !== '' ? $s['anchor'] : $s['type'] . '-' . $s['id'],
            'label'  => $s['label'],
            'short'  => match ($s['type']) {
                'intro'    => 'About',
                'services' => 'The Villa',
                'why'      => 'Why Us',
                'process'  => 'How It Works',
                'areas'    => 'Location',
                'reviews'  => 'Reviews',
                'faq'      => 'FAQ',
                'contact'  => 'Contact',
                default    => $s['label'],
            },
        ];
    }
}
$phone     = setting('phone');
$email     = setting('email');
$themeVars = theme_css_vars();
$schema    = schema_org_json();
$origin    = site_origin();
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(setting('seo_title', $businessName)) ?></title>
<meta name="description" content="<?= e(setting('seo_description')) ?>">
<link rel="canonical" href="<?= e($origin . url('/')) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e(setting('seo_title', $businessName)) ?>">
<meta property="og:description" content="<?= e(setting('seo_description')) ?>">
<meta property="og:url" content="<?= e($origin . url('/')) ?>">
<meta property="og:site_name" content="<?= e($businessName) ?>">
<?php if (setting('og_image') !== ''): ?>
<meta property="og:image" content="<?= e(setting('og_image')) ?>">
<?php endif; ?>
<meta name="theme-color" content="#12100d">
<link rel="icon" href="<?= e(url('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="preload" href="<?= e(url('assets/fonts/space-grotesk-700.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(url('assets/fonts/manrope-400.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('assets/css/main.css')) ?>">
<?php if ($themeVars !== ''): ?>
<style><?= $themeVars ?></style>
<?php endif; ?>
<?php if ($schema !== ''): ?>
<script type="application/ld+json"><?= $schema ?></script>
<?php endif; ?>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<div class="scroll-progress" aria-hidden="true"><span id="scroll-progress-bar"></span></div>

<?php if ($preview): ?>
<div class="preview-banner" role="status">Draft preview — this is how unsaved-to-live content will look. <a href="<?= e(url('/')) ?>">View live</a> · <a href="<?= e(url('/admin/')) ?>">Back to admin</a></div>
<?php endif; ?>

<header class="site-header" id="site-header">
  <nav class="nav-pill" aria-label="Primary">
    <a class="nav-logo" href="<?= e(url('/')) ?>#home">
      <span class="nav-logo__mark" aria-hidden="true"></span>
      <span class="nav-logo__name"><?= e($businessName) ?></span>
    </a>
    <ul class="nav-links">
      <?php foreach ($navItems as $item): ?>
      <li><a href="#<?= e($item['anchor']) ?>" data-nav-link="<?= e($item['anchor']) ?>"><?= e($item['short']) ?></a></li>
      <?php endforeach; ?>
    </ul>
    <a class="btn btn--primary btn--sm nav-cta" data-magnetic href="#contact">Enquire</a>
    <button class="nav-burger" id="nav-burger" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
      <span></span><span></span>
    </button>
  </nav>
</header>

<div class="mobile-menu" id="mobile-menu" hidden>
  <div class="mobile-menu__inner">
    <ul class="mobile-menu__list">
      <li><a href="#home" data-menu-link><span class="mobile-menu__num">01</span> Home</a></li>
      <?php foreach ($navItems as $i => $item): ?>
      <li><a href="#<?= e($item['anchor']) ?>" data-menu-link><span class="mobile-menu__num"><?= str_pad((string) ($i + 2), 2, '0', STR_PAD_LEFT) ?></span> <?= e($item['short']) ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="mobile-menu__foot">
      <?php if ($email !== ''): ?><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><?php endif; ?>
      <?php if ($phone !== ''): ?><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a><?php endif; ?>
    </div>
  </div>
</div>

<nav class="side-dots" id="side-dots" aria-label="Section progress">
  <?php foreach ($sections as $i => $s):
      $anchor = $s['anchor'] !== '' ? $s['anchor'] : $s['type'] . '-' . $s['id']; ?>
  <a href="#<?= e($anchor) ?>" data-dot="<?= e($anchor) ?>">
    <span class="side-dots__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
    <span class="side-dots__label"><?= e($s['label']) ?></span>
  </a>
  <?php endforeach; ?>
</nav>

<main id="main">
<?php foreach ($sections as $i => $section) {
    render_section($section, $i);
} ?>
</main>

<?php include APP_ROOT . '/templates/sections/footer.php'; ?>

<div class="mobile-cta" id="mobile-cta" aria-hidden="false">
  <?php if ($phone !== ''): ?>
  <a class="btn btn--ghost" href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>">Call</a>
  <?php endif; ?>
  <a class="btn btn--primary" href="#contact">Get a quote</a>
</div>

<div class="cursor" id="cursor" aria-hidden="true"><span class="cursor__label"></span></div>

<script type="module" src="<?= e(asset('assets/js/main.js')) ?>"></script>
</body>
</html>
