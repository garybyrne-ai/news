<?php

declare(strict_types=1);

require __DIR__ . '/_admin.php';

ensure_session();
if (is_admin()) {
    header('Location: ' . url('/admin/'));
    exit;
}

$error = null;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // Simple lockout: 8 attempts, 10-minute cool-off, per session.
    $_SESSION['login_attempts'] ??= 0;
    $_SESSION['login_locked_until'] ??= 0;

    if (time() < $_SESSION['login_locked_until']) {
        $error = 'Too many attempts — try again in a few minutes.';
    } elseif (!csrf_check($_POST['_token'] ?? null)) {
        $error = 'Session expired — please try again.';
    } elseif (password_verify((string) ($_POST['password'] ?? ''), setting('admin_password_hash'))) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        $_SESSION['login_attempts'] = 0;
        header('Location: ' . url('/admin/'));
        exit;
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= 8) {
            $_SESSION['login_locked_until'] = time() + 600;
            $_SESSION['login_attempts'] = 0;
        }
        $error = 'Incorrect password.';
    }
}

admin_header('Log in');
?>
<div class="login-card">
  <h1>Administration</h1>
  <p class="muted">Log in to edit the website.</p>
  <?php if ($error): ?><p class="notice notice--err"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required autofocus autocomplete="current-password">
    <button class="btn" type="submit">Log in</button>
  </form>
</div>
<?php admin_footer(); ?>
