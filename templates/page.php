<?php /** @var array $page */ ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page['title']) ?> — <?= e(setting('business_name')) ?></title>
<meta name="robots" content="noindex, follow">
<link rel="icon" href="<?= e(url('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= e(asset('assets/css/main.css')) ?>">
</head>
<body class="legal-page">
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header is-scrolled">
  <nav class="nav-pill" aria-label="Primary">
    <a class="nav-logo" href="<?= e(url('/')) ?>">
      <span class="nav-logo__mark" aria-hidden="true"></span>
      <span class="nav-logo__name"><?= e(setting('business_name')) ?></span>
    </a>
    <a class="btn btn--primary btn--sm nav-cta" href="<?= e(url('/')) ?>#contact">Enquire</a>
  </nav>
</header>
<main id="main" class="legal-main">
  <div class="container container--narrow">
    <h1><?= e($page['title']) ?></h1>
    <div class="legal-body"><?= $page['body'] /* trusted admin HTML */ ?></div>
    <p><a class="btn btn--ghost" href="<?= e(url('/')) ?>">← Back to the villa</a></p>
  </div>
</main>
</body>
</html>
