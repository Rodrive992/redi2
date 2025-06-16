<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesa';
    
    protected $fillable = [
        'usuario',
        'entrada',
        'nombre',
        'dependencia',
        'entregado_a',
        'estado',
        'fecha'  // Solo las columnas que realmente existen
    ];
    
    // Solo incluir 'fecha' si necesitas que se maneje como objeto Carbon
    protected $dates = [
        'fecha'  // Eliminados created_at y updated_at que no existen
    ];
    
    // Desactivar timestamps automáticos
    public $timestamps = false;
    
    public function scopePendientesDe($query, $usuario)
    {
        return $query->where('entregado_a', $usuario)
                    ->where('estado', 'Pendiente')
                    ->orderBy('fecha', 'desc');
    }
}