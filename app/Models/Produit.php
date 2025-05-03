<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'slug',
        'prix',
        'devise',
        'description',
        'image',
        'posted',
        'image_url',
        'categorie_id',
        'user_id',
        'tags',
        'nature',
    ];

    protected $casts = [
        'posted' => 'boolean',
        'prix' => 'decimal:2'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }
}

