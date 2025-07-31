<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivosRealPrestacion extends Model
{
    protected $table = 'archivos_real_prestacion'; // Nombre exacto de tu tabla
    
    protected $fillable = [
        'nombre_archivo',
        'ruta_archivo',
        'tipo_archivo',
        'tamano_archivo',
        'fecha_subida',
        'dependencia',
        'mes',
        'ano',
        'usuario_envio',
        'usuario_auto',
        'estado',
        'desempenio'
    ];
    
    // Si no usas timestamps (created_at y updated_at)
    public $timestamps = false;
}
