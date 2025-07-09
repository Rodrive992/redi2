<?php

namespace App\Http\Controllers;

use App\Models\AsignacionLegajos;
use App\Models\AsignacionRelojes;
use App\Models\AsistenciaReloj;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaDgpController extends Controller
{
    public function index()
    {
        return view('herramientas.asistencia');
    }

    public function consultarInforme(Request $request)
    {
        $request->validate([
            'dependencia' => 'required',
            'desempenio' => 'nullable',
            'desde' => 'required|date',
            'hasta' => 'required|date',
            'nombre_legajo' => 'nullable|string'
        ]);

        $datos = $this->procesarAsistencia($request);

        return view('herramientas.asistencia', [
            'desde' => $request->desde,
            'hasta' => $request->hasta,
            'dependencia' => $request->dependencia,
            'desempenio' => $request->desempenio,
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

        // Inicializamos la estructura vacía para todos los legajos y días
        $asistencia = [];
        foreach ($legajosFiltrados as $legajo) {
            foreach ($fechas as $fecha) {
                $asistencia[$legajo][$fecha] = [];
            }
        }

        // Procesamos los registros
        foreach ($registros as $r) {
            $legajo = $r->legajo;
            $fecha = $r->fecha;
            $hora = substr($r->registro, 0, 5);

            $asistencia[$legajo][$fecha][] = $hora;
        }

        // Filtro de 40 minutos
        foreach ($asistencia as $legajo => $dias) {
            foreach ($dias as $fecha => $horas) {
                $horas = array_unique($horas);
                sort($horas);

                $filtrados = [];
                $ultimoMinutos = null;

                foreach ($horas as $hora) {
                    list($h, $m) = explode(':', $hora);
                    $minutosActual = $h * 60 + $m;

                    if (is_null($ultimoMinutos)) {
                        $filtrados[] = $hora;
                        $ultimoMinutos = $minutosActual;
                    } else {
                        $diff = $minutosActual - $ultimoMinutos;
                        if ($diff >= 40) {
                            $filtrados[] = $hora;
                            $ultimoMinutos = $minutosActual;
                        }
                    }
                }

                $asistencia[$legajo][$fecha] = $filtrados;
            }
        }

        // Ordenar por nombre
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