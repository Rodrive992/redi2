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
     public function asistenciaExternoUai()
    {
        return view('herramientas.asistencia_externo_uai', [
            'title' => 'Asistencia Uai'
        ]);
    }

    
    public function realPrestacion()
    {
        return view('herramientas.real_prestacion_externo', [
            'title' => 'Real Prestación'
        ]);
    }
     public function realPrestacionHistorialExterno()
    {
        return view('herramientas.real_prestacion_historial_externo', [
            'title' => 'Real Prestación Historial'
        ]);
    }

   
}
