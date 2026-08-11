<?php

declare(strict_types=1);

require __DIR__ . '/_admin.php';
require_admin();
admin_require_post_token();

$pdo = Database::pdo();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $pdo->prepare('DELETE FROM leads WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
    flash_set('admin_ok', 'Lead deleted.');
    header('Location: ' . url('/admin/leads.php'));
    exit;
}

$leads = $pdo->query('SELECT * FROM leads ORDER BY id DESC LIMIT 500')->fetchAll();

admin_header('Leads');
?>
<div class="page-head">
  <h1>Enquiries</h1>
  <p class="muted"><?= count($leads) ?> received. Newest first.</p>
</div>

<?php if (!$leads): ?>
<p class="muted">No enquiries yet — they will appear here as soon as the contact form is used.</p>
<?php else: ?>
<div class="leads">
  <?php foreach ($leads as $lead): ?>
  <article class="lead-card">
    <header>
      <strong><?= e($lead['name']) ?></strong>
      <span class="muted"><?= e($lead['created_at']) ?> UTC</span>
    </header>
    <p class="lead-card__meta">
      <a href="mailto:<?= e($lead['email']) ?>"><?= e($lead['email']) ?></a>
      <?php if ($lead['phone']): ?> · <?= e($lead['phone']) ?><?php endif; ?>
      <?php if ($lead['topic']): ?> · <span class="badge badge--on"><?= e($lead['topic']) ?></span><?php endif; ?>
    </p>
    <p class="lead-card__msg"><?= nl2br(e($lead['message'])) ?></p>
    <form method="post" class="inline"><?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $lead['id'] ?>">
      <button class="btn btn--sm btn--danger" name="action" value="delete"
              onclick="return confirm('Delete this enquiry?')">Delete</button>
    </form>
  </article>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php admin_footer(); ?>
