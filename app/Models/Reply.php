<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'content',
        'author_name',
        'author_email',
        'ip_adress',
        'status',
        'parent_id'  // Ajout du champ pour les réponses imbriquées
    ];

    protected $nullable = ['user_id'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
