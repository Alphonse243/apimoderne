<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use App\Models\Produit;
use App\Models\Comment;
use Illuminate\Support\Str;
require_once '../../vendor/autoload.php';
// require_once __DIR__ . '/../../bootstrap/app.php';
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_DATABASE'] ?? 'blog-php-moderne',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
class ProduitSeeder 
{
    public function run(): void
    {
        // Création d'une instance Faker en français
        $faker = Factory::create('fr_FR');

        // Nettoyer la table 
        Produit::truncate();
        echo "Table nettoyer  avec succès !\n";

        // inserer 1000 elements
        for ($i = 0; $i < 1000; $i++) {
            $nom = $faker->words(3, true);
            
            $produit = Produit::create([
                'nom' => ucfirst($nom),
                'slug' => Str::slug($nom),
                'description' => $faker->paragraphs(10, true),
                'prix' => $faker->randomFloat(2, 10, 1000),
                'devise' => $faker->randomElement(['USD', 'CDF']),
                'nature' => $faker->randomElement(['arrivage', 'reduction', 'solde']),
                'tags' => json_encode($faker->words(rand(2, 5))),
                'posted' => rand(0, 10),
                'categorie_id' => rand(1, 10),
                'image_url' => $faker->imageUrl(640, 480, 'products', true),
                'user_id' => rand(1, 10),
            ]);

        }
    
        // Pour les commentaire 
        $produits = Produit::all();
        foreach ($produits as $item) {
            for ($i = 0; $i < 5; $i++) {
                Comment::create([
                    'content' => $faker->paragraph(),
                    'user_id' => rand(1,50),
                    'post_id' => $item->id
                ]);
            }
        }
        echo'seeding comment avec success';
    }
} 


$seeder = new ProduitSeeder();
$seeder->run();

echo "Base de données initialisée avec succès !\n";