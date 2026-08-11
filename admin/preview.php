<?php

declare(strict_types=1);

require __DIR__ . '/_admin.php';
require_admin();

admin_header('Preview');
$previewUrl = url('/') . '?preview=1';
?>
<div class="page-head">
  <h1>Draft preview</h1>
  <p class="muted">Shows draft content (saved but not yet published). Publish from each section's edit page.
    <a href="<?= e($previewUrl) ?>" target="_blank" rel="noopener">Open full preview ↗</a></p>
</div>

<div class="preview-toolbar">
  <button class="btn btn--sm is-active" data-w="100%">Desktop</button>
  <button class="btn btn--sm" data-w="820px">Tablet</button>
  <button class="btn btn--sm" data-w="390px">Mobile</button>
</div>

<div class="preview-frame-wrap">
  <iframe id="preview-frame" src="<?= e($previewUrl) ?>" title="Site preview"></iframe>
</div>

<script>
document.querySelectorAll('.preview-toolbar button').forEach((btn) => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.preview-toolbar button').forEach((b) => b.classList.remove('is-active'));
    btn.classList.add('is-active');
    document.getElementById('preview-frame').style.width = btn.dataset.w;
  });
});
</script>
<?php admin_footer(); ?>
