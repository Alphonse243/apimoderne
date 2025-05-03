<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

// Supprimer les tables existantes dans l'ordre inverse des dépendances
$tables = ['post_images', 'comments', 'posts', 'categories', 'users'];
foreach ($tables as $table) {
    if (Capsule::schema()->hasTable($table)) {
        Capsule::schema()->drop($table);
    }
}

// Création de la table categories
Capsule::schema()->create('categories', function ($table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('description')->nullable();
    $table->timestamps();
});

// Création de la table users
Capsule::schema()->create('users', function ($table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('role')->default('user');
    $table->string('avatar')->nullable();
    $table->text('bio')->nullable();
    $table->string('website')->nullable();
    $table->string('facebook')->nullable();
    $table->string('twitter')->nullable();
    $table->string('instagram')->nullable();
    $table->string('phone')->nullable();
    $table->string('address')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('email_verified_at')->nullable();
    $table->timestamps();
});

// Création de la table posts
Capsule::schema()->create('posts', function ($table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('extret');
    $table->text('content');
    $table->foreignId('user_id')->constrained();
    $table->foreignId('category_id')->constrained();
    $table->string('status')->default('1');
    $table->timestamps();
});

// Création de la table comments
Capsule::schema()->create('comments', function ($table) {
    $table->id();
    $table->text('content');
    $table->foreignId('user_id')->constrained();
    $table->foreignId('post_id')->constrained();
    $table->string('ip_adress')->default('0');;
    $table->timestamps();
});

// Ajout de la table post_images après la table posts
Capsule::schema()->create('post_images', function ($table) {
    $table->id();
    $table->foreignId('post_id')->constrained()->onDelete('cascade');
    $table->string('image_path');
    $table->string('alt_text')->nullable();
    $table->boolean('is_featured')->default(false);
    $table->integer('order')->default(0);
    $table->timestamps();
});
