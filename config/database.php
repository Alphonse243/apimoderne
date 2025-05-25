<?php

use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_DATABASE'] ?? 'api-global',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// return [
//     'driver' => 'mysql',
//     'host' => $_ENV['DB_HOST'] ?? 'localhost',
//     'database' => $_ENV['DB_DATABASE'] ?? 'blog-php-moderne',
//     'username' => $_ENV['DB_USERNAME'] ?? 'root',
//     'password' => $_ENV['DB_PASSWORD'] ?? '',
//     'charset' => 'utf8mb4',
//     'collation' => 'utf8mb4_unicode_ci',
//     'prefix' => '',
// ];

// Configuration du route
define("base_url", "http://localhost/karma-master/"); // URL du site
define("Route_path",$_SERVER["DOCUMENT_ROOT"].'/karma-master/'); //LE dossier root du serbeur
$route = base_url;