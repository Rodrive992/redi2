<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PlantaUnca extends Model
{
    // Definir el nombre de la tabla dinámicamente en el constructor del modelo
    protected $table;

    // Establecemos los campos que se pueden rellenar
    protected $fillable = [
        'id_cargo', 'legajo', 'nombre', 'dni', 'fecha_ingreso', 
        'dependencia', 'dependencia_comp', 'escalafon', 'agrupamiento', 
        'subrogancia', 'cargo', 'nro_cargo', 'caracter', 'dedicacion', 
        'alta_cargo', 'vencimiento_cargo', 'vencimiento_cargo1', 'hs', 
        'licencia', 'desempenio', 'estado_baja', 'fecha_observ', 
        'estado_observ', 'puntaje', 'alta_licencia', 'baja_licencia'
    ];

    // No necesitamos timestamps automáticos, ya que la tabla no tiene `created_at` y `updated_at`
    public $timestamps = false;

    /**
     * Constructor del modelo para definir la tabla con nombre dinámico.
     */
    public function __construct(array $attributes = [])
    {
        $currentMonth = Carbon::now()->format('m'); // Mes actual
        $currentYear = Carbon::now()->format('Y');  // Año actual
        
        // Definir la tabla dinámica como 'planta_{mes}_{año}'
        $this->table = 'planta_' . $currentMonth . '_' . $currentYear;

        parent::__construct($attributes);
    }
}
