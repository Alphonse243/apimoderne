<?php

namespace App\Controllers;

use App\Models\Comment;
use Exception;

class CommentController
{
    public function createComment(array $data)
    {
        try {
            if (empty($data['post_id']) || empty($data['content'])) {
                throw new Exception('Post ID et contenu sont requis');
            }

            $commentData = [
                'post_id' => $data['post_id'],
                'content' => strip_tags($data['content']),
                'ip_adress' => $data['ip_adress'],
                'status' => 1,
                'is_anonymous' => !isset($data['user_id'])
            ];

            // Ajouter les informations d'utilisateur authentifié
            if (isset($data['user_id'])) {
                $commentData['user_id'] = $data['user_id'];
                $commentData['author_name'] = $data['author_name'];
                $commentData['author_email'] = $data['author_email'];
            } else {
                // Informations pour utilisateur anonyme
                $commentData['user_id'] = null;
                $commentData['author_name'] = $data['author_name'] ?: 'Anonyme';
                $commentData['author_email'] = $data['author_email'] ?: 'anonymous@example.com';
            }

            $comment = Comment::create($commentData);

            return $comment->load('author')->toArray();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la création du commentaire: ' . $e->getMessage());
        }
    }
}
