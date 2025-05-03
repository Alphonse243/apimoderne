<?php

require_once __DIR__ . '/../bootstrap/app.php';
require_once __DIR__ . '/migrations/create_tables.php';

use Database\Seeders\DatabaseSeeder;

$seeder = new DatabaseSeeder();
$seeder->run();

echo "Base de données initialisée avec succès !\n";
