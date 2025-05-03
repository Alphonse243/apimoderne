<?php
namespace App\Controllers;
use App\Models\User;
class HomeController
{
    public function index()
    {
        $pdo = require __DIR__ . '/../../config/database.php';
        
        // Récupérer les derniers articles
        $query = $pdo->query('
            SELECT p.*, u.name as author_name, pi.image_path 
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.id 
            LEFT JOIN post_images pi ON p.id = pi.post_id AND pi.is_featured = 1
            WHERE p.status = "published" 
            ORDER BY p.created_at DESC 
            LIMIT 6
        ');
        
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../home';
    }
    public function home(){
        $pdo = require __DIR__ . '/../../config/database.php';
        
        // Récupérer les derniers articles
        $query = $pdo->query('
            SELECT * FROM users
        ');
        
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return($posts);
    }
}
