<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProcedimientosController extends Controller
{
    public function index()
    {
        $procedimientos = [
            'alta_vida' => 'Alta seguro de vida',
            'baja_vida' => 'Baja seguro de vida',
            'alta_sepelio' => 'Alta seguro de sepelio',
            'baja_sepelio' => 'Baja seguro de sepelio',
            'toma_posesion' => 'Toma de posesión',
            'adicional_titulo_posgrado' => 'Adicional por título de posgrado',
            'desiganciones_automaticas' => 'Designaciones automáticas',
            'licencia_maternidad' => 'Licencia por maternidad',
        ];

        return view('herramientas.procedimientos', compact('procedimientos'));
    }

    public function verProcedimiento($procedimiento)
    {
        if (!View::exists("procedimientos.$procedimiento")) {
            abort(404);
        }

        return view("procedimientos.$procedimiento");
    }
}
