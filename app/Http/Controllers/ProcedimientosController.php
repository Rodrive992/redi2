<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProcedimientosController extends Controller
{
    public function index(Request $request)
    {
        $procedimiento = $request->query('procedimiento');
        $procedimientoSeleccionado = null;

        if ($procedimiento && View::exists("herramientas.procedimientos.$procedimiento")) {
            $procedimientoSeleccionado = $procedimiento;
        }

        return view('herramientas.procedimientos', compact('procedimientoSeleccionado'));
    }
}
