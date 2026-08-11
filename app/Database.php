<?php

declare(strict_types=1);

/**
 * Minimal PDO/SQLite wrapper. Creates the schema and seeds default
 * content on first run so the site works straight after deployment.
 */
final class Database
{
    private static ?PDO $pdo = null;

    public static function boot(): void
    {
        if (self::$pdo !== null) {
            return;
        }
        if (!is_dir(DATA_DIR)) {
            mkdir(DATA_DIR, 0775, true);
        }
        $fresh = !file_exists(DB_PATH);
        self::$pdo = new PDO('sqlite:' . DB_PATH, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        self::$pdo->exec('PRAGMA journal_mode = WAL');
        self::$pdo->exec('PRAGMA foreign_keys = ON');
        self::migrate();
        if ($fresh || !self::hasSections()) {
            require_once APP_ROOT . '/app/seed.php';
            seed_database(self::$pdo);
        }
        // Idempotent: merges content introduced by newer code into
        // databases seeded by older versions, then stamps the version.
        require_once APP_ROOT . '/app/upgrade.php';
        upgrade_database(self::$pdo);
    }

    public static function pdo(): PDO
    {
        return self::$pdo;
    }

    private static function migrate(): void
    {
        self::$pdo->exec(<<<'SQL'
            CREATE TABLE IF NOT EXISTS settings (
                key   TEXT PRIMARY KEY,
                value TEXT NOT NULL DEFAULT ''
            );
            CREATE TABLE IF NOT EXISTS sections (
                id                INTEGER PRIMARY KEY AUTOINCREMENT,
                type              TEXT NOT NULL,
                label             TEXT NOT NULL DEFAULT '',
                anchor            TEXT NOT NULL DEFAULT '',
                enabled           INTEGER NOT NULL DEFAULT 1,
                sort              INTEGER NOT NULL DEFAULT 0,
                background        TEXT NOT NULL DEFAULT 'default',
                css_class         TEXT NOT NULL DEFAULT '',
                content_draft     TEXT NOT NULL DEFAULT '{}',
                content_published TEXT NOT NULL DEFAULT '{}',
                updated_at        TEXT NOT NULL DEFAULT (datetime('now'))
            );
            CREATE TABLE IF NOT EXISTS pages (
                slug       TEXT PRIMARY KEY,
                title      TEXT NOT NULL DEFAULT '',
                body       TEXT NOT NULL DEFAULT '',
                updated_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
            CREATE TABLE IF NOT EXISTS leads (
                id         INTEGER PRIMARY KEY AUTOINCREMENT,
                name       TEXT NOT NULL DEFAULT '',
                email      TEXT NOT NULL DEFAULT '',
                phone      TEXT NOT NULL DEFAULT '',
                topic      TEXT NOT NULL DEFAULT '',
                message    TEXT NOT NULL DEFAULT '',
                consent    INTEGER NOT NULL DEFAULT 0,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
            SQL);
    }

    private static function hasSections(): bool
    {
        return (int) self::$pdo->query('SELECT COUNT(*) FROM sections')->fetchColumn() > 0;
    }
}
