<?php
/**
 * Post Controller
 * 
 * Handles all post related operations including:
 * - Post CRUD operations
 * - View tracking
 * - Post status management
 * - Image handling
 */

namespace App\Controllers;
use App\models\User; 
use App\models\Post; 
use App\models\PostImage; 
use App\models\Comment; 
use App\models\Category; 
use App\models\PostView;
use Carbon\Carbon;
use PDO;
use PDOException;
use Exception;

class PostController
{   
    /**
     * Récupère tous les posts avec pagination pour AJAX
     * @param int $page Page actuelle
     * @param int $limite Nombre d'éléments par page
     * @return mixed
     */
    public static function getAllPostsAjax($page , $limite)
    {
        // Calculer l'offset pour la pagination
        $offset = $page-1;

        return Post::with(['author', 'images', 'comments'])
            ->orderBy('created_at', 'DESC') // S'assurer que les posts sont triés par date
            ->offset($offset)
            ->limit($limite)
        ->get();
    }

    /**
     * Récupère tous les posts avec pagination pour AJAX par utilisateur
     * @param int $page Page actuelle
     * @param int $limite Nombre d'éléments par page
     * @return mixed
     */
    public static function getAllPostsAjaxByUser($page , $limite, $user_id)
    {
        return Post::with(['author','comments','images','categorie'])->orderBy('created_at','desc')->where('user_id',$user_id)
        ->skip($page)
        ->take($limite)
        ->get();
    }


    /**
     * Récupère les posts par catégorie avec pagination
     * @param int $page Page actuelle
     * @param int $limite Nombre d'éléments par page
     * @param int $categorie_id ID de la catégorie
     * @return mixed
     */
    public static function getAllPostsByIdAjax($page , $limite, $categorie_id)
    {
        $categories = Category::where('id',$categorie_id);
        return Post::with(['author','comments','images','categorie'])
        ->where('category_id', $categorie_id )
        ->skip($page)
        ->take($limite)
        ->get();
    }

    public static function getAllPosts()
    {
       return Post::with(['author','comments','images','categorie'])->orderBy('created_at','desc')->get();
    }

    public static function show($slug)
    {
        $post = Post::with(['author', 'comments', 'images', 'categorie'])
            ->where('slug', $slug)
            ->first();

        if ($post) {
            // Création d'une instance pour utiliser incrementViews
            $instance = new self();
            $instance->incrementViews($post->id, $_SERVER['REMOTE_ADDR']);
        }

        return $post;
    }

    /**
     * Increment view count for a post by IP
     * Ensures unique views per IP address
     *
     * @param int $postId Post ID
     * @param string $ipAddress Visitor IP
     * @return void
     */
    private function incrementViews($postId, $ipAddress)
    {
        try {
            PostView::firstOrCreate(
                ['post_id' => $postId, 'ip_address' => $ipAddress]
            );

            // Mettre à jour le compteur de vues dans la table posts
            $viewCount = PostView::where('post_id', $postId)->count();
            Post::where('id', $postId)->update(['views' => $viewCount]);
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas interrompre l'affichage
            error_log("Erreur lors de l'incrémentation des vues: " . $e->getMessage());
        }
    }

    public function getPostViews($postId)
    {
        return PostView::where('post_id', $postId)->count();
    }

    public function getPostsByUser($userId)
    {
        return Post::with(['author', 'comments', 'images', 'categorie'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
    }

    public function createPost(array $data): ?int
    {
        try {
            $slug = strtolower(str_replace(' ', '-', $data['title']));
            $count = Post::where('slug', 'LIKE', $slug . '%')->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            $post = Post::create([
                'title' => strip_tags($data['title']),
                'slug' => $slug,
                'extret'=> $data['extret'],
                'content' => $data['content'],
                'category_id' => (int)$data['category_id'],
                'user_id' => (int)$data['user_id'],
                'status' => (int)$data['status']
            ]);

            return $post ? $post->id : null;

        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPostById($id)
    {
        return Post::with(['author', 'comments', 'images', 'categorie'])
            ->where('id', $id)
            ->first();
    }

    /**
     * Met à jour un post existant
     * @param int $id ID du post
     * @param array $data Données du post
     * @return bool
     */
    public function updatePost(int $id, array $data): bool
    {
        try {
            // Recherche du post
            $post = Post::find($id);
            if (!$post) return false;

            // Mise à jour des données de base
            $updated = $post->update([
                'title' => strip_tags($data['title']),
                'content' => $data['content'],
                'extret'  => $data['extret'],
                'category_id' => (int)$data['category_id'],
                'status' => (int)$data['status']
            ]);

            // Gestion du téléchargement des images
            if (isset($data['images']) && is_array($data['images'])) {
                $files = $data['images'];
                
                // Création du répertoire de stockage
                $storageDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/posts';
                if (!file_exists($storageDir)) {
                    if (!mkdir($storageDir, 0777, true)) {
                        throw new \Exception("Failed to create storage directory");
                    }
                }

                // Traitement de chaque image
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $files['tmp_name'][$i];
                        $fileName = time() . '_' . basename($files['name'][$i]);
                        $uploadPath = $storageDir . '/' . $fileName;
                        
                        // Sauvegarde de l'image
                        if (move_uploaded_file($tmpName, $uploadPath)) {
                            PostImage::create([
                                'post_id' => $id,
                                'image_path' => 'public/img/blog/imge-secondaire/' . $fileName,
                                'is_featured' => false
                            ]);
                        } else {
                            error_log("Failed to move uploaded file: " . error_get_last()['message']);
                        }
                    }
                }
            }

            return $updated;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getPostStatus($postId) {
        return Post::where('id', $postId)->value('status');
    }

    public function updatePostStatus($postId, $newStatus) {
        try {
            return Post::where('id', $postId)->update([
                'status' => $newStatus,
                'updated_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            throw new Exception("Erreur lors de la mise à jour du statut : " . $e->getMessage());
        }
    }

    public function deletePost($postId) {
        try {
            $post = Post::with(['comments', 'images'])->find($postId);
            if (!$post) {
                throw new Exception("Post non trouvé");
            }

            // Commencer une transaction
            \DB::beginTransaction();
            
            try {
                // Supprimer les commentaires
                Comment::where('post_id', $postId)->delete();
                
                // Supprimer les images
                PostImage::where('post_id', $postId)->delete();
                
                // Supprimer le post
                $post->delete();
                
                \DB::commit();
                return true;
            } catch (\Exception $e) {
                \DB::rollback();
                throw new Exception("Erreur de suppression en cascade : " . $e->getMessage());
            }
        } catch (\Exception $e) {
            throw new Exception("Erreur lors de la suppression du post : " . $e->getMessage());
        }
    }

    /**
     * Get popular posts based on view count
     *
     * @param int $limit Number of posts to return
     * @return array Popular posts with their images
     */
    public function getPopularPosts($limit = 4)
    {
        return Post::with(['images'])
            ->where('status', 1)
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($post) {
                $featuredImage = $post->images->firstWhere('is_featured', true);
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'created_at' => $post->created_at,
                    'image_path' => $featuredImage ? $featuredImage->image_path : 'img/blog/popular-post/post1.jpg'
                ];
            });
    }
}