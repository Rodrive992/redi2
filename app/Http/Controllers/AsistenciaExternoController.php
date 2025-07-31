<?php

namespace App\Http\Controllers;

use App\Models\AsignacionControlAsistencia;
use App\Models\AsignacionLegajos;
use App\Models\AsignacionRelojes;
use App\Models\AsistenciaReloj;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaExternoController extends Controller
{
    public function consultarInforme(Request $request)
    {
        $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date',
            'nombre_legajo' => 'nullable|string'
        ]);
        
        // Obtengo el usuario autenticado
        $user = Auth::user();

        // Buscamos en asignacion_control_asistencia
        $asignacion = AsignacionControlAsistencia::where('cuil_usuario', $user->cuil)->first();

        // Validación si no existe asignación
        if (!$asignacion) {
            return redirect()->back()->with('error', 'No tiene asignación de control de asistencia.');
        }

        $dependencia = $asignacion->dependencia_usuario ?? null;
        $desempenio = $asignacion->desempenio_usuario ?? null;

        // Pasamos estos valores al request para que lo use procesarAsistencia
        $request->merge([
            'dependencia' => $dependencia,
            'desempenio' => $desempenio
        ]);

        $datos = $this->procesarAsistencia($request);

        return view('herramientas.asistencia_externo', [
            'desde' => $request->desde,
            'hasta' => $request->hasta,
            'dependencia' => $dependencia,
            'desempenio' => $desempenio,
            'nombre_legajo' => $request->nombre_legajo,
            'fechas' => $datos['fechas'],
            'asistencia' => $datos['asistencia'],
            'nombres' => $datos['nombres']
        ]);
    }

    private function buscarLegajos(Request $request)
    {
        $legajos = AsignacionLegajos::where('dependencia_usuario', $request->dependencia);

        if ($request->dependencia === 'sgrl' && !$request->desempenio) {
            $legajos->where(function ($q) {
                $q->whereNull('desempenio_usuario')->orWhere('desempenio_usuario', '!=', 'dgom');
            });
        } elseif ($request->desempenio) {
            $legajos->where('desempenio_usuario', $request->desempenio);
        }

        if ($request->nombre_legajo) {
            $legajos->where(function ($q) use ($request) {
                $q->where('nombre_legajo', 'like', '%' . $request->nombre_legajo . '%')
                    ->orWhere('legajo', 'like', '%' . $request->nombre_legajo . '%');
            });
        }

        return $legajos->get();
    }

    private function buscarRelojes(Request $request)
    {
        $relojes = AsignacionRelojes::where('dependencia_usuario', $request->dependencia);

        if ($request->dependencia === 'sgrl' && !$request->desempenio) {
            $relojes->where(function ($q) {
                $q->whereNull('desempenio_usuario')->orWhere('desempenio_usuario', '!=', 'dgom');
            });
        } elseif ($request->desempenio) {
            $relojes->where('desempenio_usuario', $request->desempenio);
        }

        return $relojes->pluck('reloj')->toArray();
    }

    private function buscarAsistencia(Request $request, array $legajosFiltrados, array $relojesFiltrados)
    {
        $asistencia = AsistenciaReloj::query();

        if (!empty($legajosFiltrados)) {
            $asistencia->whereIn('legajo', $legajosFiltrados);
        }

        if (!empty($relojesFiltrados)) {
            $asistencia->whereIn('reloj', $relojesFiltrados);
        }

        if ($request->nombre_legajo) {
            $asistencia->where(function ($q) use ($request) {
                $q->where('nombre_apellido', 'like', '%' . $request->nombre_legajo . '%')
                    ->orWhere('legajo', 'like', '%' . $request->nombre_legajo . '%');
            });
        }

        $asistencia->whereBetween('fecha', [$request->desde, $request->hasta]);

        return $asistencia->orderBy('legajo')->orderBy('fecha')->orderBy('registro')->get();
    }

    private function procesarAsistencia(Request $request)
    {
        $legajos = $this->buscarLegajos($request);
        $relojes = $this->buscarRelojes($request);

        $legajosFiltrados = $legajos->pluck('legajo')->toArray();
        $nombres = $legajos->pluck('nombre_legajo', 'legajo')->toArray();

        $registros = $this->buscarAsistencia($request, $legajosFiltrados, $relojes);

        $fechas = [];
        $desde = Carbon::parse($request->desde);
        $hasta = Carbon::parse($request->hasta);

        for ($fecha = $desde->copy(); $fecha <= $hasta; $fecha->addDay()) {
            $fechas[] = $fecha->format('Y-m-d');
        }

        $asistencia = [];
        foreach ($legajosFiltrados as $legajo) {
            foreach ($fechas as $fecha) {
                $asistencia[$legajo][$fecha] = [];
            }
        }

        foreach ($registros as $r) {
            $legajo = $r->legajo;
            $fecha = $r->fecha;
            $hora = substr($r->registro, 0, 5);
            $asistencia[$legajo][$fecha][] = $hora;
        }

        foreach ($asistencia as $legajo => $dias) {
            foreach ($dias as $fecha => $horas) {
                $horas = array_unique($horas);
                sort($horas);

                $filtrados = [];
                $ultimoMinutos = null;

                foreach ($horas as $hora) {
                    [$h, $m] = explode(':', $hora);
                    $minutosActual = $h * 60 + $m;

                    if (is_null($ultimoMinutos)) {
                        $filtrados[] = $hora;
                        $ultimoMinutos = $minutosActual;
                    } elseif (($minutosActual - $ultimoMinutos) >= 40) {
                        $filtrados[] = $hora;
                        $ultimoMinutos = $minutosActual;
                    }
                }

                $asistencia[$legajo][$fecha] = $filtrados;
            }
        }

        uksort($asistencia, function ($a, $b) use ($nombres) {
            return strcmp($nombres[$a] ?? '', $nombres[$b] ?? '');
        });

        return [
            'fechas' => $fechas,
            'asistencia' => $asistencia,
            'nombres' => $nombres
        ];
    }
}