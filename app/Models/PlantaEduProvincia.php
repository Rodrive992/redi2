<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PlantaEduProvincia extends Model
{
    
   // Definir el nombre de la tabla
    protected $table = 'planta_educacion_provincia';  // Cambia el nombre de la tabla si es necesario
    
    // Definir las columnas que se pueden llenar (mass assignable)
    protected $fillable = [
        'id_cargo', 'legajo', 'nombre', 'dni', 'fecha_ingreso', 
        'dependencia', 'dependencia_comp', 'escalafon', 'agrupamiento', 
        'subrogancia', 'cargo', 'nro_cargo', 'caracter', 'dedicacion', 
        'alta_cargo', 'vencimiento_cargo', 'hs', 
        'licencia', 'desempenio', 'estado_baja', 
        'puntaje', 'alta_licencia', 'baja_licencia'
    ];
    
    // Si las fechas están en formato específico, debes indicarlo
    protected $dates = [
        'alta_cargo',
        'alta_licencia',
        'baja_licencia'
    ];

    // Desactivar los timestamps automáticos si no usas created_at y updated_at
    public $timestamps = false;

    // Puedes agregar más funciones o relaciones si es necesario
}
