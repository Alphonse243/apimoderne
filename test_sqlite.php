<?php
// Test script to verify sqlite connection via Illuminate Capsule

// Force sqlite for the test (override .env)
$_ENV['DB_DRIVER'] = 'sqlite';
// Put test DB inside database/ folder
$_ENV['DB_DATABASE'] = 'database/test_db.sqlite';

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Database\Capsule\Manager as Capsule;

try {
    // Create a simple table if not exists
    if (!Capsule::schema()->hasTable('test_connections')) {
        Capsule::schema()->create('test_connections', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    // Insert a row
    $id = Capsule::table('test_connections')->insertGetId(['name' => 'sqlite-test']);

    $row = Capsule::table('test_connections')->where('id', $id)->first();

    echo "OK: inserted id={$id} name={$row->name}\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
