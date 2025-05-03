<?php

namespace App\Controllers;

use App\Models\Reply;
use Exception;

class ReplyController
{
    public function createReply(array $data)
    {
        try {
            if (empty($data['comment_id']) || empty($data['content'])) {
                throw new Exception('Comment ID et contenu sont requis');
            }

            $replyData = [
                'comment_id' => $data['comment_id'],
                'content' => strip_tags($data['content']),
                'ip_adress' => $data['ip_adress'],
                'parent_id' => $data['parent_id'] ?? null,  // Ajout du parent_id
                'status' => 1
            ];

            if (isset($data['user_id'])) {
                $replyData['user_id'] = $data['user_id'];
                $replyData['author_name'] = $data['author_name'];
                $replyData['author_email'] = $data['author_email'];
            } else {
                $replyData['user_id'] = null;
                $replyData['author_name'] = $data['author_name'] ?: 'Anonyme';
                $replyData['author_email'] = $data['author_email'] ?: 'anonymous@example.com';
            }

            $reply = Reply::create($replyData);
            return $reply->load('author')->toArray();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la création de la réponse: ' . $e->getMessage());
        }
    }
}
