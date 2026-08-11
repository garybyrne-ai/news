<?php

declare(strict_types=1);

require __DIR__ . '/_admin.php';
require_admin();
admin_require_post_token();

$pdo  = Database::pdo();
$id   = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM sections WHERE id = ?');
$stmt->execute([$id]);
$section = $stmt->fetch();

if (!$section) {
    flash_set('admin_err', 'Section not found.');
    header('Location: ' . url('/admin/'));
    exit;
}

$fields = SECTION_FIELDS[$section['type']] ?? [];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // Section meta
    $label     = trim((string) ($_POST['label'] ?? $section['label']));
    $anchor    = strtolower(trim((string) ($_POST['anchor'] ?? $section['anchor'])));
    $anchor    = preg_replace('/[^a-z0-9-]/', '', $anchor) ?? '';
    $bg        = in_array($_POST['background'] ?? '', SECTION_BACKGROUNDS, true) ? $_POST['background'] : $section['background'];
    $cssClass  = preg_replace('/[^a-zA-Z0-9 _-]/', '', (string) ($_POST['css_class'] ?? '')) ?? '';
    $enabled   = !empty($_POST['enabled']) ? 1 : 0;

    // Content fields
    $content = [];
    foreach ($fields as $key => $def) {
        $raw = (string) ($_POST['f_' . $key] ?? '');
        if (($def[1] ?? 'text') === 'json') {
            $decoded = json_decode($raw, true);
            if ($raw !== '' && !is_array($decoded)) {
                $errors[] = sprintf('"%s" is not valid JSON — the field was not changed.', $def[0]);
                $decoded = section_content($section, true)[$key] ?? [];
            }
            $content[$key] = $decoded ?: [];
        } else {
            $content[$key] = str_replace("\r\n", "\n", $raw);
        }
    }
    $json = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $publish = ($_POST['do'] ?? '') === 'publish';
    $pdo->prepare(
        'UPDATE sections SET label = ?, anchor = ?, background = ?, css_class = ?, enabled = ?,
                content_draft = ?' . ($publish ? ', content_published = ?' : '') . ",
                updated_at = datetime('now')
         WHERE id = ?"
    )->execute($publish
        ? [$label, $anchor, $bg, $cssClass, $enabled, $json, $json, $id]
        : [$label, $anchor, $bg, $cssClass, $enabled, $json, $id]);

    if ($errors) {
        flash_set('admin_err', implode(' ', $errors));
    } else {
        flash_set('admin_ok', $publish ? 'Section published.' : 'Draft saved — visible in preview only.');
    }
    header('Location: ' . url('/admin/section.php') . '?id=' . $id);
    exit;
}

$content = section_content($section, true); // draft-first for editing

admin_header('Edit — ' . $section['label']);
?>
<div class="page-head">
  <h1>Edit: <?= e($section['label']) ?></h1>
  <p class="muted">
    Type <code><?= e($section['type']) ?></code> ·
    <a href="<?= e(url('/')) ?>?preview=1#<?= e($section['anchor']) ?>" target="_blank" rel="noopener">Preview draft ↗</a> ·
    <a href="<?= e(url('/admin/')) ?>">← All sections</a>
  </p>
</div>

<form method="post" class="edit-form">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= $section['id'] ?>">

  <fieldset>
    <legend>Section settings</legend>
    <div class="grid-2">
      <div class="field"><label for="m-label">Admin label</label>
        <input id="m-label" name="label" value="<?= e($section['label']) ?>"></div>
      <div class="field"><label for="m-anchor">Anchor ID (for #links)</label>
        <input id="m-anchor" name="anchor" value="<?= e($section['anchor']) ?>" pattern="[a-z0-9-]*"></div>
      <div class="field"><label for="m-bg">Background style</label>
        <select id="m-bg" name="background">
          <?php foreach (SECTION_BACKGROUNDS as $bg): ?>
          <option value="<?= $bg ?>" <?= $section['background'] === $bg ? 'selected' : '' ?>><?= ucfirst($bg) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="field"><label for="m-class">Custom CSS class (optional)</label>
        <input id="m-class" name="css_class" value="<?= e($section['css_class']) ?>"></div>
      <div class="field field--check">
        <input type="checkbox" id="m-enabled" name="enabled" <?= $section['enabled'] ? 'checked' : '' ?>>
        <label for="m-enabled">Section enabled (visible on the site)</label>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Content</legend>
    <?php foreach ($fields as $key => $def):
        [$label, $kind] = $def;
        $hint  = $def[2] ?? null;
        $value = $content[$key] ?? '';
        if ($kind === 'json') {
            $value = $value === '' ? '' : json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    ?>
    <div class="field">
      <label for="f-<?= e($key) ?>"><?= e($label) ?></label>
      <?php if ($kind === 'text'): ?>
      <input id="f-<?= e($key) ?>" name="f_<?= e($key) ?>" value="<?= e((string) $value) ?>">
      <?php elseif ($kind === 'textarea'): ?>
      <textarea id="f-<?= e($key) ?>" name="f_<?= e($key) ?>" rows="3"><?= e((string) $value) ?></textarea>
      <?php else: ?>
      <textarea id="f-<?= e($key) ?>" name="f_<?= e($key) ?>" rows="9" class="mono" spellcheck="false"><?= e((string) $value) ?></textarea>
      <?php if ($hint): ?><p class="hint">Format: <code><?= e($hint) ?></code></p><?php endif; ?>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </fieldset>

  <div class="actions">
    <button class="btn btn--ghost" name="do" value="draft">Save draft</button>
    <button class="btn" name="do" value="publish">Save &amp; publish</button>
  </div>
</form>
<?php admin_footer(); ?>
