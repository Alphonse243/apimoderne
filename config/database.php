<?php

// This file returns a connection array compatible with Illuminate Database (Eloquent).
// It supports MySQL (default) and SQLite when DB_DRIVER=sqlite.

// Allow selecting driver via environment variable. Defaults to mysql.
$driver = $_ENV['DB_DRIVER'] ?? 'mysql';

if ($driver === 'sqlite') {
    // DB_DATABASE should be a path to the sqlite file. Default to project/database/database.sqlite
    $defaultSqlite = __DIR__ . '/../database/database.sqlite';
    $databasePath = $_ENV['DB_DATABASE'] ?? $defaultSqlite;

    // If the path is relative, make it relative to project root
    if (!str_starts_with($databasePath, '/') && !str_starts_with($databasePath, "\\")) {
        $databasePath = __DIR__ . '/../' . $databasePath;
    }

    // Ensure directory exists and file exists (touch)
    $dbDir = dirname($databasePath);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }
    if (!file_exists($databasePath)) {
        touch($databasePath);
    }

    return [
        'driver' => 'sqlite',
        'database' => $databasePath,
        'prefix' => '',
    ];
} else {
    return [
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'database' => $_ENV['DB_DATABASE'] ?? 'api-global',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ];
}