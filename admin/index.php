<?php

declare(strict_types=1);

require __DIR__ . '/_admin.php';
require_admin();
admin_require_post_token();

$pdo = Database::pdo();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $id     = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');
    $stmt   = $pdo->prepare('SELECT * FROM sections WHERE id = ?');
    $stmt->execute([$id]);
    $section = $stmt->fetch();

    if ($section) {
        switch ($action) {
            case 'toggle':
                $pdo->prepare('UPDATE sections SET enabled = 1 - enabled WHERE id = ?')->execute([$id]);
                flash_set('admin_ok', sprintf('"%s" is now %s.', $section['label'], $section['enabled'] ? 'hidden' : 'visible'));
                break;

            case 'up':
            case 'down':
                $dir  = $action === 'up' ? '<' : '>';
                $ord  = $action === 'up' ? 'DESC' : 'ASC';
                $swap = $pdo->prepare("SELECT id, sort FROM sections WHERE sort $dir ? ORDER BY sort $ord LIMIT 1");
                $swap->execute([$section['sort']]);
                if ($other = $swap->fetch()) {
                    $pdo->prepare('UPDATE sections SET sort = ? WHERE id = ?')->execute([$other['sort'], $id]);
                    $pdo->prepare('UPDATE sections SET sort = ? WHERE id = ?')->execute([$section['sort'], $other['id']]);
                }
                break;

            case 'duplicate':
                $pdo->prepare(
                    'INSERT INTO sections (type, label, anchor, enabled, sort, background, css_class, content_draft, content_published)
                     VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?)'
                )->execute([
                    $section['type'], $section['label'] . ' (copy)', '',
                    $section['sort'] + 1, $section['background'], $section['css_class'],
                    $section['content_draft'], $section['content_published'],
                ]);
                flash_set('admin_ok', 'Section duplicated (hidden until you enable it).');
                break;

            case 'delete':
                $pdo->prepare('DELETE FROM sections WHERE id = ?')->execute([$id]);
                flash_set('admin_ok', 'Section deleted.');
                break;
        }
    }
    header('Location: ' . url('/admin/'));
    exit;
}

$sections = $pdo->query('SELECT * FROM sections ORDER BY sort ASC, id ASC')->fetchAll();

admin_header('Sections');
?>
<div class="page-head">
  <h1>Homepage sections</h1>
  <p class="muted">Reorder, hide, duplicate or edit any section. Drafts are only visible in preview until published.</p>
</div>

<table class="table">
  <thead>
    <tr><th>Order</th><th>Section</th><th>Type</th><th>Anchor</th><th>Status</th><th class="ta-r">Actions</th></tr>
  </thead>
  <tbody>
  <?php foreach ($sections as $i => $s): ?>
    <tr class="<?= $s['enabled'] ? '' : 'is-disabled' ?>">
      <td class="reorder">
        <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $s['id'] ?>">
          <button name="action" value="up" title="Move up" <?= $i === 0 ? 'disabled' : '' ?>>↑</button>
          <button name="action" value="down" title="Move down" <?= $i === count($sections) - 1 ? 'disabled' : '' ?>>↓</button>
        </form>
      </td>
      <td><strong><?= e($s['label']) ?></strong>
        <?php if ($s['content_draft'] !== $s['content_published']): ?><span class="badge badge--draft">draft changes</span><?php endif; ?>
      </td>
      <td><code><?= e($s['type']) ?></code></td>
      <td><code>#<?= e($s['anchor']) ?></code></td>
      <td><span class="badge <?= $s['enabled'] ? 'badge--on' : 'badge--off' ?>"><?= $s['enabled'] ? 'Visible' : 'Hidden' ?></span></td>
      <td class="ta-r">
        <a class="btn btn--sm" href="<?= e(url('/admin/section.php')) ?>?id=<?= $s['id'] ?>">Edit</a>
        <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $s['id'] ?>">
          <button class="btn btn--sm btn--ghost" name="action" value="toggle"><?= $s['enabled'] ? 'Hide' : 'Show' ?></button>
          <button class="btn btn--sm btn--ghost" name="action" value="duplicate">Duplicate</button>
          <button class="btn btn--sm btn--danger" name="action" value="delete"
                  onclick="return confirm('Delete &quot;<?= e($s['label']) ?>&quot;? This cannot be undone.')">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php admin_footer(); ?>
