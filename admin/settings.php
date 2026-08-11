<?php

declare(strict_types=1);

require __DIR__ . '/_admin.php';
require_admin();
admin_require_post_token();

$groups = [
    'Business' => [
        'business_name'     => ['Business name', 'text'],
        'business_tagline'  => ['Tagline', 'text'],
        'business_category' => ['Category (eyebrow context)', 'text'],
        'status_text'       => ['Status chip text (e.g. "Enquiries open")', 'text'],
        'geo_label'         => ['Coordinates label on the hero (e.g. 28.92° N · 13.66° W)', 'text'],
        'hours'             => ['Opening hours / availability note', 'text'],
    ],
    'Contact' => [
        'phone'    => ['Phone (blank hides call buttons)', 'text'],
        'email'    => ['Email', 'text'],
        'address'  => ['Address', 'text'],
        'locality' => ['Locality (town)', 'text'],
        'region'   => ['Region', 'text'],
        'country'  => ['Country code (ISO, e.g. ES)', 'text'],
        'instagram' => ['Instagram URL', 'text'],
        'facebook'  => ['Facebook URL', 'text'],
    ],
    'Contact form' => [
        'lead_notify_email' => ['Send enquiry emails to (blank = store in Leads only)', 'text'],
        'lead_from_email'   => ['Send emails from this address (blank = noreply@your-domain)', 'text'],
    ],
    'SEO & schema' => [
        'seo_title'       => ['Meta title', 'text'],
        'seo_description' => ['Meta description', 'textarea'],
        'og_image'        => ['OpenGraph image URL', 'text'],
        'schema_enabled'  => ['Structured data (1 = on, 0 = off)', 'text'],
        'faq_single_open' => ['FAQ: only one open at a time (1/0)', 'text'],
    ],
    'Theme colours (hex, blank = design default)' => [
        'color_bg'        => ['Background', 'color'],
        'color_surface'   => ['Surface', 'color'],
        'color_text'      => ['Text', 'color'],
        'color_muted'     => ['Muted text', 'color'],
        'color_primary'   => ['Primary', 'color'],
        'color_secondary' => ['Secondary', 'color'],
        'color_accent'    => ['Accent', 'color'],
        'color_border'    => ['Border', 'color'],
        'color_success'   => ['Success', 'color'],
    ],
];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (($_POST['do'] ?? '') === 'password') {
        $new = (string) ($_POST['new_password'] ?? '');
        if (strlen($new) < 10) {
            flash_set('admin_err', 'Password must be at least 10 characters.');
        } else {
            setting_set('admin_password_hash', password_hash($new, PASSWORD_DEFAULT));
            flash_set('admin_ok', 'Password updated.');
        }
    } else {
        foreach ($groups as $fields) {
            foreach ($fields as $key => $def) {
                if (isset($_POST['s_' . $key])) {
                    $value = trim((string) $_POST['s_' . $key]);
                    if ($def[1] === 'color' && $value !== '' && !preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) {
                        continue; // ignore invalid colours rather than break the theme
                    }
                    setting_set($key, $value);
                }
            }
        }
        flash_set('admin_ok', 'Settings saved.');
    }
    header('Location: ' . url('/admin/settings.php'));
    exit;
}

admin_header('Settings');
?>
<div class="page-head">
  <h1>Site settings</h1>
  <p class="muted">Business details, SEO and theme. Blank values are hidden on the site rather than shown empty.</p>
</div>

<form method="post" class="edit-form">
  <?= csrf_field() ?>
  <?php foreach ($groups as $legend => $fields): ?>
  <fieldset>
    <legend><?= e($legend) ?></legend>
    <div class="grid-2">
      <?php foreach ($fields as $key => [$label, $kind]): $value = setting($key); ?>
      <div class="field <?= $kind === 'textarea' ? 'field--full' : '' ?>">
        <label for="s-<?= e($key) ?>"><?= e($label) ?></label>
        <?php if ($kind === 'textarea'): ?>
        <textarea id="s-<?= e($key) ?>" name="s_<?= e($key) ?>" rows="3"><?= e($value) ?></textarea>
        <?php elseif ($kind === 'color'): ?>
        <div class="color-pair">
          <input type="color" value="<?= e($value ?: '#000000') ?>" aria-hidden="true" tabindex="-1"
                 oninput="this.nextElementSibling.value = this.value">
          <input id="s-<?= e($key) ?>" name="s_<?= e($key) ?>" value="<?= e($value) ?>" placeholder="default">
        </div>
        <?php else: ?>
        <input id="s-<?= e($key) ?>" name="s_<?= e($key) ?>" value="<?= e($value) ?>">
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </fieldset>
  <?php endforeach; ?>
  <div class="actions"><button class="btn" type="submit">Save settings</button></div>
</form>

<form method="post" class="edit-form">
  <?= csrf_field() ?>
  <fieldset>
    <legend>Change admin password</legend>
    <div class="field">
      <label for="new-password">New password (min. 10 characters)</label>
      <input id="new-password" type="password" name="new_password" minlength="10" autocomplete="new-password" required>
    </div>
  </fieldset>
  <div class="actions"><button class="btn btn--danger" name="do" value="password">Update password</button></div>
</form>
<?php admin_footer(); ?>
