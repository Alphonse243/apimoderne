<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RememberToken extends Model
{
    protected $fillable = ['user_id', 'token', 'ip_address', 'expires_at'];
    protected $table = 'remember_tokens';
    
    // Spécifier les colonnes de timestamps si elles ont des noms différents
    const CREATED_AT = 'created_at';  
    const UPDATED_AT = 'updated_at';

    public $timestamps = true;
    protected $dates = ['expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
