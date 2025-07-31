<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesa';
    
    protected $fillable = [
        'usuario',
        'entrada',
        'nombre',
        'dependencia',
        'entregado_a',
        'estado',
        'fecha'
    ];
    
    protected $dates = [
        'fecha'
    ];
    
    public $timestamps = false;
    
    public function scopePendientesDe($query, $usuario)
    {
        return $query->where('entregado_a', $usuario)
                    ->where('estado', 'Pendiente')
                    ->orderBy('id', 'desc');
    }
}