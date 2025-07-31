<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionControlAsistencia extends Model
{
    protected $table = 'asignacion_control_asistencia';
    public $timestamps = false;

    protected $fillable = [
        'nombre_usuario',
        'cuil_usuario',
        'dependencia_usuario',
        'desempenio_usuario'
    ];
}
