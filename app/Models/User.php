<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'phone',
        'address',
        'is_active',
        'email_verified_at',
        'is_email_verified',
        'email_verification_token',
        'is_phone_verified',
        'phone_verification_token'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }
        
        if ($this->avatar) {
            return '/karma-master/uploads/avatars/' . $this->avatar;
        }
        
        return 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($this->name);
    }
}
