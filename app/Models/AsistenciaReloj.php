<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaReloj extends Model
{
    protected $table = 'asistencia_reloj';
    public $timestamps = false;

    protected $fillable = [
        'reloj',
        'legajo',
        'nombre_apellido',
        'dependencia',
        'fecha',
        'registro',
        'observacion',
        'agregado',
        'usuario_agregado',
        'fecha_agregado',
        'hora_agregado'
    ];
}

