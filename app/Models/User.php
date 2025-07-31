<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'cuil',
        'dependencia',
        'desempenio'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
