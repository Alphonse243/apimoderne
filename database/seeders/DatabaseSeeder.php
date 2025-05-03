<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\PostImage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder
{
    public function run()
    {
        $faker = Factory::create('fr_FR');

        // Seed Categories
        $categories = [
            'Technologie',
            'Lifestyle',
            'Voyage',
            'Cuisine',
            'Sport',
            'Politics',
            'Fashion',
            'Architecture',
            'Food',
            'Art',
            'Adventure',
        ];
        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => $faker->sentence()
            ]);
        }

        // Seed Users
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => $i === 0 ? 'admin' : 'user',
                'avatar' => $faker->imageUrl(150, 150, 'people'),
                'bio' => $faker->text(200),
                'website' => $faker->url(),
                'facebook' => 'https://facebook.com/' . $faker->userName(),
                'twitter' => 'https://twitter.com/' . $faker->userName(),
                'instagram' => 'https://instagram.com/' . $faker->userName(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'email_verified_at' => Carbon::now(),
                'is_active' => true
            ]);
        }

        // Seed Posts
        $users = User::all();
        $categories = Category::all();

        foreach ($users as $user) {
            for ($i = 0; $i < 3; $i++) {
                $title = $faker->sentence();
                Post::create([
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'extret' => $faker->paragraphs(3, true),
                    'content' => $faker->paragraphs(10, true),
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id,
                    'status' => $faker->randomElement(['1', '0'])
                ]);
            }
        }

        // Ajout du seeding des images
        $blogImagesSupplémentaires = glob(__DIR__ . '/../../public/img/blog/imge-secondaire/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $blogImagesPrincipale = glob(__DIR__ . '/../../public/img/blog/latest-post/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        foreach (Post::all() as $post) {
            // Image principale
            PostImage::create([
                'post_id' => $post->id,
                'image_path' => 'public/img/blog/latest-post/' . basename($blogImagesPrincipale[array_rand($blogImagesPrincipale)]),
                'alt_text' => $post->title,
                'is_featured' => true,
                'order' => 0
            ]);

            // Images supplémentaires (0 à 3)
            $extraImagesCount = rand(0, 3);
            for ($i = 0; $i < $extraImagesCount; $i++) {
                PostImage::create([
                    'post_id' => $post->id,
                    'image_path' => 'public/img/blog/imge-secondaire/' . basename($blogImagesSupplémentaires[array_rand($blogImagesSupplémentaires)]),
                    'alt_text' => $faker->sentence(3),
                    'is_featured' => false,
                    'order' => $i + 1
                ]);
            }
        }

        // Seed Comments
        $posts = Post::all();
        foreach ($posts as $post) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                Comment::create([
                    'content' => $faker->paragraph(),
                    'user_id' => $users->random()->id,
                    'post_id' => $post->id
                ]);
            }
        }
    }
}
