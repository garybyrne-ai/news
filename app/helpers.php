<?php

declare(strict_types=1);

/** HTML-escape. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/* ---------------------------------------------------------------- settings */

function settings_all(): array
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (Database::pdo()->query('SELECT key, value FROM settings') as $row) {
            $cache[$row['key']] = $row['value'];
        }
    }
    return $cache;
}

function setting(string $key, string $default = ''): string
{
    return settings_all()[$key] ?? $default;
}

function setting_set(string $key, string $value): void
{
    Database::pdo()
        ->prepare('INSERT INTO settings (key, value) VALUES (?, ?)
                   ON CONFLICT(key) DO UPDATE SET value = excluded.value')
        ->execute([$key, $value]);
}

/* ---------------------------------------------------------------- sections */

/**
 * Ordered list of sections for rendering. In preview mode the draft
 * content is used (falling back to published) and admin auth is required.
 */
function sections_for_render(bool $preview = false): array
{
    $rows = Database::pdo()
        ->query('SELECT * FROM sections WHERE enabled = 1 ORDER BY sort ASC, id ASC')
        ->fetchAll();
    foreach ($rows as &$row) {
        $row['content'] = section_content($row, $preview);
    }
    return $rows;
}

function section_content(array $row, bool $preview = false): array
{
    $published = json_decode($row['content_published'] ?: '{}', true) ?: [];
    if (!$preview) {
        return $published;
    }
    $draft = json_decode($row['content_draft'] ?: '{}', true) ?: [];
    return $draft ?: $published;
}

/** Section by type (first enabled match), used for schema.org output. */
function section_by_type(string $type): ?array
{
    $stmt = Database::pdo()->prepare(
        'SELECT * FROM sections WHERE type = ? AND enabled = 1 ORDER BY sort ASC LIMIT 1'
    );
    $stmt->execute([$type]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/* -------------------------------------------------------------- admin auth */

function is_admin(): bool
{
    ensure_session();
    return !empty($_SESSION['admin']);
}

function require_admin(): void
{
    if (!is_admin()) {
        header('Location: ' . url('/admin/login.php'));
        exit;
    }
}

/* -------------------------------------------------------------------- csrf */

function csrf_token(): string
{
    ensure_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_check(?string $token): bool
{
    ensure_session();
    return is_string($token) && hash_equals($_SESSION['csrf'] ?? '', $token);
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

/* ------------------------------------------------------------------- flash */

function flash_set(string $key, string $message): void
{
    ensure_session();
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    ensure_session();
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

/* -------------------------------------------------------------------- urls */

/** Base path of the app (supports installation in a sub-directory). */
function base_path(): string
{
    static $base = null;
    if ($base === null) {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $dir    = str_replace('\\', '/', dirname($script));
        if (str_ends_with($dir, '/admin')) {
            $dir = substr($dir, 0, -6);
        }
        $base = rtrim($dir, '/');
    }
    return $base;
}

function url(string $path = '/'): string
{
    return base_path() . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $file = APP_ROOT . '/' . ltrim($path, '/');
    $v    = is_file($file) ? substr((string) filemtime($file), -6) : '1';
    return url($path) . '?v=' . $v;
}

/** Resolve a CMS-entered media path: absolute URLs pass through,
 *  repo-relative paths (e.g. assets/img/villa/pool.jpg) get the base path. */
function media_url(string $path): string
{
    if ($path === '' || preg_match('#^(https?:)?//#', $path)) {
        return $path;
    }
    return url($path);
}

/** width/height attributes for a local image so the layout reserves
 *  space before load (correct lazy-loading + zero CLS). */
function img_size_attrs(string $path): string
{
    static $cache = [];
    if (preg_match('#^(https?:)?//#', $path)) {
        return '';
    }
    if (!isset($cache[$path])) {
        $file = APP_ROOT . '/' . ltrim($path, '/');
        $size = is_file($file) ? @getimagesize($file) : false;
        $cache[$path] = $size ? ' width="' . $size[0] . '" height="' . $size[1] . '"' : '';
    }
    return $cache[$path];
}

function site_origin(): string
{
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $host  = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return ($https ? 'https://' : 'http://') . $host;
}

/* ------------------------------------------------------------------- theme */

/** Inline CSS-variable overrides from CMS theme settings. */
function theme_css_vars(): string
{
    $map = [
        'color_bg'        => '--color-bg',
        'color_surface'   => '--color-surface',
        'color_text'      => '--color-text',
        'color_muted'     => '--color-muted',
        'color_primary'   => '--color-primary',
        'color_secondary' => '--color-secondary',
        'color_accent'    => '--color-accent',
        'color_border'    => '--color-border',
        'color_success'   => '--color-success',
    ];
    $vars = '';
    foreach ($map as $key => $var) {
        $value = trim(setting($key));
        if ($value !== '' && preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) {
            $vars .= $var . ':' . $value . ';';
        }
    }
    return $vars === '' ? '' : ':root{' . $vars . '}';
}

/** Render a section template. */
function render_section(array $section, int $index): void
{
    $file = APP_ROOT . '/templates/sections/' . basename($section['type']) . '.php';
    if (!is_file($file)) {
        return;
    }
    $content = $section['content'];
    $anchor  = $section['anchor'] !== '' ? $section['anchor'] : $section['type'] . '-' . $section['id'];
    $number  = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
    include $file;
}

/** data-* attributes + classes shared by every section wrapper. */
function section_attrs(array $section, string $anchor, int $index): string
{
    $classes = ['section', 'section--' . $section['type'], 'bg-' . $section['background']];
    if ($section['css_class'] !== '') {
        $classes[] = $section['css_class'];
    }
    return 'id="' . e($anchor) . '" class="' . e(implode(' ', $classes)) . '"'
        . ' data-section-index="' . $index . '" data-section-label="' . e($section['label']) . '"';
}
