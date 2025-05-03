<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = ['email', 'token', 'expires_at'];
    public $timestamps = false;
    protected $table = 'password_resets';
    
    protected $dates = ['expires_at'];
}
