<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HerramientasExternoController extends Controller
{
   
    public function asistenciaExterno()
    {
        return view('herramientas.asistencia_externo', [
            'title' => 'Asistencia'
        ]);
    }

    
    public function realPrestacionExterno()
    {
        return view('herramientas.real_prestacion_externo', [
            'title' => 'Real Prestación'
        ]);
    }

   
}
