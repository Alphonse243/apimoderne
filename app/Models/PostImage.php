<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $fillable = ['post_id', 'image_path', 'alt_text', 'is_featured', 'order'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
