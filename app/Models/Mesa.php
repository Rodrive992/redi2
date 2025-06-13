<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesa';
    protected $fillable = [
        'entrada', 'dependencia', 'nombre', 
        'estado', 'entregado_a', 'recibido_por',
        'fecha', 'fecha_recibido', 'observaciones',
        'reenviado_por', 'fecha_reenviado'
    ];
    
    protected $dates = [
        'fecha',
        'fecha_recibido',
        'fecha_reenviado',
        'created_at',
        'updated_at'
    ];
    
    public function scopePendientesDe($query, $usuario)
    {
        return $query->where('entregado_a', $usuario)
                    ->where('estado', 'Pendiente')
                    ->orderBy('fecha', 'desc');
    }
}