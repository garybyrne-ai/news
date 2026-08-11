<?php

declare(strict_types=1);

/**
 * Content upgrader. Existing databases (seeded by an older version of
 * the code) are brought up to date on the next request:
 *
 *  - settings keys introduced later are inserted (never overwritten)
 *  - section content keys introduced later are merged into both draft
 *    and published content — ONLY where the key is missing, so admin
 *    edits are never touched
 *  - section types introduced later (e.g. the photo gallery) are
 *    inserted at their seed position
 *
 * Bump CONTENT_VERSION whenever seed content gains keys or sections.
 */
const CONTENT_VERSION = 2;

function upgrade_database(PDO $pdo): void
{
    $current = (int) ($pdo->query("SELECT value FROM settings WHERE key = 'content_version'")->fetchColumn() ?: 1);
    if ($current >= CONTENT_VERSION) {
        return;
    }

    require_once APP_ROOT . '/app/seed.php';

    // 1. New settings keys (INSERT OR IGNORE never clobbers existing values)
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)');
    foreach (seed_default_settings() as $key => $value) {
        $stmt->execute([$key, $value]);
    }

    // 2. Merge new content keys into existing sections + insert new types
    $existing = [];
    foreach ($pdo->query('SELECT * FROM sections') as $row) {
        $existing[$row['type']][] = $row;
    }
    $maxSort = (int) $pdo->query('SELECT COALESCE(MAX(sort), 0) FROM sections')->fetchColumn();

    $update = $pdo->prepare(
        "UPDATE sections SET content_draft = ?, content_published = ?, updated_at = datetime('now') WHERE id = ?"
    );
    $insert = $pdo->prepare(
        'INSERT INTO sections (type, label, anchor, enabled, sort, background, css_class, content_draft, content_published)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $previousSort = 0;
    foreach (seed_default_sections() as $seed) {
        $type = $seed['type'];
        if (isset($existing[$type])) {
            foreach ($existing[$type] as $row) {
                $draft     = merge_missing_keys(json_decode($row['content_draft'] ?: '{}', true) ?: [], $seed['content']);
                $published = merge_missing_keys(json_decode($row['content_published'] ?: '{}', true) ?: [], $seed['content']);
                $update->execute([
                    json_encode($draft, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    json_encode($published, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    $row['id'],
                ]);
                $previousSort = max($previousSort, (int) $row['sort']);
            }
        } else {
            // Slot the new section right after the section that precedes
            // it in the seed order; shift later sections down.
            $sort = $previousSort > 0 ? $previousSort + 1 : $maxSort + 10;
            $pdo->prepare('UPDATE sections SET sort = sort + 2 WHERE sort > ?')->execute([$previousSort]);
            $json = json_encode($seed['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $insert->execute([
                $type, $seed['label'], $seed['anchor'],
                $seed['enabled'] ?? 1, $sort,
                $seed['background'], '', $json, $json,
            ]);
            $previousSort = $sort;
        }
    }

    // 3. Fill top-level content values that exist but are empty strings
    //    when the seed now provides a real default (e.g. image paths
    //    that shipped as '' in v1).
    foreach ($pdo->query('SELECT * FROM sections')->fetchAll() as $row) {
        $seedContent = null;
        foreach (seed_default_sections() as $seed) {
            if ($seed['type'] === $row['type']) {
                $seedContent = $seed['content'];
                break;
            }
        }
        if ($seedContent === null) {
            continue;
        }
        $changed   = false;
        $draft     = fill_empty_defaults(json_decode($row['content_draft'] ?: '{}', true) ?: [], $seedContent, $changed);
        $published = fill_empty_defaults(json_decode($row['content_published'] ?: '{}', true) ?: [], $seedContent, $changed);
        if ($changed) {
            $update->execute([
                json_encode($draft, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                json_encode($published, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $row['id'],
            ]);
        }
    }

    $pdo->prepare("INSERT INTO settings (key, value) VALUES ('content_version', ?)
                   ON CONFLICT(key) DO UPDATE SET value = excluded.value")
        ->execute([(string) CONTENT_VERSION]);
}

/** Add seed keys that the stored content is missing entirely. */
function merge_missing_keys(array $content, array $seed): array
{
    foreach ($seed as $key => $value) {
        if (!array_key_exists($key, $content)) {
            $content[$key] = $value;
        }
    }
    return $content;
}

/**
 * Where the stored value is '' but the seed now has a non-empty default
 * (image paths added in v2), adopt the default. List items are matched
 * by index for their 'image'/'src' keys only.
 */
function fill_empty_defaults(array $content, array $seed, bool &$changed): array
{
    foreach ($seed as $key => $seedValue) {
        if (!array_key_exists($key, $content)) {
            continue;
        }
        if (is_string($seedValue) && $seedValue !== '' && $content[$key] === ''
            && (str_contains($key, 'image') || $key === 'src')) {
            $content[$key] = $seedValue;
            $changed = true;
        }
        if (is_array($seedValue) && is_array($content[$key])) {
            foreach ($seedValue as $i => $seedItem) {
                if (!is_array($seedItem) || !isset($content[$key][$i]) || !is_array($content[$key][$i])) {
                    continue;
                }
                foreach (['image', 'src'] as $imgKey) {
                    if (isset($seedItem[$imgKey]) && $seedItem[$imgKey] !== ''
                        && ($content[$key][$i][$imgKey] ?? null) === '') {
                        $content[$key][$i][$imgKey] = $seedItem[$imgKey];
                        $changed = true;
                    }
                }
            }
        }
    }
    return $content;
}
