<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $fillable = [
        'name',
        'acronym',
        'courte',
        'description'
    ];

}
