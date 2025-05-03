<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'extret',
        'content',
        'user_id',
        'category_id',
        
        'status'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Categorie()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function featuredImage()
    {
        return $this->hasOne(PostImage::class)->where('is_featured', true);
    }
}
