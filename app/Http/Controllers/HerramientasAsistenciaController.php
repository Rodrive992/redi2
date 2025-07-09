<?php

namespace App\Http\Controllers;

use App\Models\AsignacionControlAsistencia;
use App\Models\AsignacionLegajos;
use App\Models\AsignacionRelojes;
use Illuminate\Http\Request;

class HerramientasAsistenciaController extends Controller
{
    public function panelControl(Request $request)
    {
        $dependencia = $request->dependencia;
        $desempenio = $request->desempenio;

        $usuariosControl = [];
        $legajos = [];
        $relojes = [];

        if ($dependencia) {
            $usuariosControl = AsignacionControlAsistencia::where('dependencia_usuario', $dependencia)
                ->when($dependencia === 'sgrl' && !$desempenio, function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereNull('desempenio_usuario')
                           ->orWhere('desempenio_usuario', '!=', 'dgom');
                    });
                })
                ->when($desempenio, fn($q) => $q->where('desempenio_usuario', $desempenio))
                ->get();

            $legajos = AsignacionLegajos::where('dependencia_usuario', $dependencia)
                ->when($dependencia === 'sgrl' && !$desempenio, function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereNull('desempenio_usuario')
                           ->orWhere('desempenio_usuario', '!=', 'dgom');
                    });
                })
                ->when($desempenio, fn($q) => $q->where('desempenio_usuario', $desempenio))
                ->orderBy('nombre_legajo', 'asc') // Ordena por nombre ascendente
                ->get();

            $relojes = AsignacionRelojes::where('dependencia_usuario', $dependencia)
                ->when($dependencia === 'sgrl' && !$desempenio, function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereNull('desempenio_usuario')
                           ->orWhere('desempenio_usuario', '!=', 'dgom');
                    });
                })
                ->when($desempenio, fn($q) => $q->where('desempenio_usuario', $desempenio))
                ->get();
        }

        return view('herramientas.panel_control', [
            'dependencia' => $dependencia,
            'desempenio' => $desempenio,
            'usuarios_control' => $usuariosControl,
            'legajos' => $legajos,
            'relojes' => $relojes
        ]);
    }

    public function guardarControl(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string',
            'cuil_usuario' => 'required|string',
            'dependencia_usuario' => 'required|string',
            'desempenio_usuario' => 'nullable|string'
        ]);

        AsignacionControlAsistencia::create([
            'nombre_usuario' => $request->nombre_usuario,
            'cuil_usuario' => $request->cuil_usuario,
            'dependencia_usuario' => $request->dependencia_usuario,
            'desempenio_usuario' => $request->desempenio_usuario
        ]);

        return redirect()->route('herramientas.panel_control', [
            'dependencia' => $request->dependencia_usuario,
            'desempenio' => $request->desempenio_usuario
        ])->with('success', 'Usuario de control guardado correctamente.');
    }

    public function guardarLegajo(Request $request)
    {
        $request->validate([
            'legajo' => 'required|string',
            'nombre_legajo' => 'required|string',
            'dependencia_usuario' => 'required|string',
            'desempenio_usuario' => 'nullable|string'
        ]);

        AsignacionLegajos::create([
            'legajo' => $request->legajo,
            'nombre_legajo' => $request->nombre_legajo,
            'dependencia_usuario' => $request->dependencia_usuario,
            'desempenio_usuario' => $request->desempenio_usuario
        ]);

        return redirect()->route('herramientas.panel_control', [
            'dependencia' => $request->dependencia_usuario,
            'desempenio' => $request->desempenio_usuario
        ])->with('success', 'Legajo asignado correctamente.');
    }

    public function guardarReloj(Request $request)
    {
        $request->validate([
            'reloj' => 'required|string',
            'dependencia_usuario' => 'required|string',
            'desempenio_usuario' => 'nullable|string'
        ]);

        AsignacionRelojes::create([
            'reloj' => $request->reloj,
            'dependencia_usuario' => $request->dependencia_usuario,
            'desempenio_usuario' => $request->desempenio_usuario
        ]);

        return redirect()->route('herramientas.panel_control', [
            'dependencia' => $request->dependencia_usuario,
            'desempenio' => $request->desempenio_usuario
        ])->with('success', 'Reloj asignado correctamente.');
    }
    // Agrega estos métodos al final de tu controlador

public function actualizarControl(Request $request, $id)
{
    $request->validate([
        'nombre_usuario' => 'required|string',
        'cuil_usuario' => 'required|string',
        'dependencia_usuario' => 'required|string',
        'desempenio_usuario' => 'nullable|string'
    ]);

    $control = AsignacionControlAsistencia::findOrFail($id);
    $control->update([
        'nombre_usuario' => $request->nombre_usuario,
        'cuil_usuario' => $request->cuil_usuario,
        'dependencia_usuario' => $request->dependencia_usuario,
        'desempenio_usuario' => $request->desempenio_usuario
    ]);

    return redirect()->route('herramientas.panel_control', [
        'dependencia' => $request->dependencia_usuario,
        'desempenio' => $request->desempenio_usuario
    ])->with('success', 'Usuario de control actualizado correctamente.');
}

public function eliminarControl($id)
{
    $control = AsignacionControlAsistencia::findOrFail($id);
    $dependencia = $control->dependencia_usuario;
    $desempenio = $control->desempenio_usuario;
    $control->delete();

    return redirect()->route('herramientas.panel_control', [
        'dependencia' => $dependencia,
        'desempenio' => $desempenio
    ])->with('success', 'Usuario de control eliminado correctamente.');
}

public function actualizarLegajo(Request $request, $id)
{
    $request->validate([
        'legajo' => 'required|string',
        'nombre_legajo' => 'required|string',
        'dependencia_usuario' => 'required|string',
        'desempenio_usuario' => 'nullable|string'
    ]);

    $legajo = AsignacionLegajos::findOrFail($id);
    $legajo->update([
        'legajo' => $request->legajo,
        'nombre_legajo' => $request->nombre_legajo,
        'dependencia_usuario' => $request->dependencia_usuario,
        'desempenio_usuario' => $request->desempenio_usuario
    ]);

    return redirect()->route('herramientas.panel_control', [
        'dependencia' => $request->dependencia_usuario,
        'desempenio' => $request->desempenio_usuario
    ])->with('success', 'Legajo actualizado correctamente.');
}

    public function eliminarLegajo($id)
    {
        $legajo = AsignacionLegajos::findOrFail($id);
        $dependencia = $legajo->dependencia_usuario;
        $desempenio = $legajo->desempenio_usuario;
        $legajo->delete();

        return redirect()->route('herramientas.panel_control', [
            'dependencia' => $dependencia,
            'desempenio' => $desempenio
        ])->with('success', 'Legajo eliminado correctamente.');
    }

    public function actualizarReloj(Request $request, $id)
    {
        $request->validate([
            'reloj' => 'required|string',
            'dependencia_usuario' => 'required|string',
            'desempenio_usuario' => 'nullable|string'
        ]);

        $reloj = AsignacionRelojes::findOrFail($id);
        $reloj->update([
            'reloj' => $request->reloj,
            'dependencia_usuario' => $request->dependencia_usuario,
            'desempenio_usuario' => $request->desempenio_usuario
        ]);

        return redirect()->route('herramientas.panel_control', [
            'dependencia' => $request->dependencia_usuario,
            'desempenio' => $request->desempenio_usuario
        ])->with('success', 'Reloj actualizado correctamente.');
    }

    public function eliminarReloj($id)
    {
        $reloj = AsignacionRelojes::findOrFail($id);
        $dependencia = $reloj->dependencia_usuario;
        $desempenio = $reloj->desempenio_usuario;
        $reloj->delete();

        return redirect()->route('herramientas.panel_control', [
            'dependencia' => $dependencia,
            'desempenio' => $desempenio
        ])->with('success', 'Reloj eliminado correctamente.');
    }
}