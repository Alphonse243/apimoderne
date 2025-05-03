<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    protected $fillable = ['post_id', 'ip_address'];
    public $timestamps = false;
    protected $table = 'post_views';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->viewed_at = $model->freshTimestamp();
        });
    }
}
