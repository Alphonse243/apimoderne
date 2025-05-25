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
use App\models\Entreprise;
use Carbon\Carbon;
use PDO;
use PDOException;
use Exception;

class EntrepriseController
{   

    public function createGettingInfo(array $data): ?int
    {
        try {
          
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

    public function updateGettingInfo(int $id, array $data): bool
    {
        try {
            // Recherche du post
            $settings = Entreprise::find($id);
            if (!$settings) return false;

            // Mise à jour des données de base
            $updated = $settings->update([
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
}