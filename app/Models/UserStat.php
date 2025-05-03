<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    protected $fillable = [
        'user_id',
        'posts_count',
        'followers_count',
        'following_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
