<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionRelojes extends Model
{
    protected $table = 'asignacion_relojes';
    public $timestamps = false;

    protected $fillable = [
        'reloj',
        'dependencia_usuario',
        'desempenio_usuario'
    ];
}