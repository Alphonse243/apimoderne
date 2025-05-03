<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'author_name',
        'author_email',
        'ip_adress',
        'status',
        'is_anonymous'  // Ajout du champ is_anonymous
    ];

    // Modifier les colonnes qui peuvent être nulles
    protected $nullable = ['user_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->orderBy('created_at', 'asc');
    }
}
