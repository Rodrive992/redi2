<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantaAdmProvincia extends Model
{
   // Definir el nombre de la tabla
    protected $table = 'planta_administracion_provincia';  // Cambia el nombre de la tabla si es necesario
    
    // Definir las columnas que se pueden llenar (mass assignable)
    protected $fillable = [
        'id_cargo',
        'nombre',
        'dni',
        'dependencia',
        'cargo',
        'caracter',
        'dedicacion',
        'puntaje',
        'hs',
        'licencia',
        'alta_cargo',
        'alta_licencia',
        'baja_licencia'
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
