<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise_contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip_address',
        'created_at',
    ];

}
