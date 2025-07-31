<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionLegajos extends Model
{
    protected $table = 'asignacion_legajos';
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'dependencia_usuario',
        'desempenio_usuario',
        'legajo',
        'nombre_legajo'
    ];
}