<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchivosRealPrestacion;

class RealPrestacionControlController extends Controller
{
    public function index(Request $request)
    {
        // Mes y año actuales si no se envían por GET
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));

        // Todas las dependencias y claves
        $dependencias = [
            'Facultad de Humanidades' => 'fhum',
            'Facultad de Cs Exactas' => 'fexa',
            'Facultad de Tecnología' => 'ftec',
            'Facultad de Derecho' => 'fder',
            'Facultad de Cs Económicas y Administración' => 'feco',
            'Facultad de Cs Agrarias' => 'fagr',
            'Facultad de Cs de la Salud' => 'fsal',
            'Escuela de Arqueología' => 'earq',
            'Escuela Preuniversitaria Fray Mamerto Esquiu' => 'efme',
            'Escuela Preuniversitaria ENET N°1' => 'enet',
            'Secretaria Academica' => 'saca',
            'Secretaría de Bienestar y Asuntos Estudiantiles' => 'sbya',
            'Secretaría Económico Financiera' => 'secf',
            'Secretaría de Extensión' => 'sext',
            'Secretaria de Investigacion y Posgrado' => 'siyp',
            'Secretaria de Relaciones Institucionales e Internacionales' => 'srii',
            'Secretaría General' => 'sgrl',
            'Secretaría General - Obras' => 'sgrl_obras',
            'Subsecretaria de Informática' => 'ssif',
            'Personal Superior Rectorado' => 'rect',
        ];

        // Desempeños especiales
        $desempenios = [
            'efme' => ['INIC', 'PRIM', 'SECU'],
            'rect' => ['UNAI', 'RECT'],
        ];

        $resultados = [];

        foreach ($dependencias as $nombreCompleto => $clave) {
            if (array_key_exists($clave, $desempenios)) {
                foreach ($desempenios[$clave] as $desempenio) {
                    $archivos = ArchivosRealPrestacion::where('dependencia', $clave)
                        ->where('mes', $mes)
                        ->where('ano', $ano)
                        ->where('desempenio', $desempenio)
                        ->orderBy('fecha_subida', 'desc')
                        ->get();

                    $resultados[] = [
                        'dependencia' => $clave,
                        'nombre' => $this->getNombreCompleto($clave, $desempenio),
                        'archivos' => $archivos,
                    ];
                }
            } else {
                $archivos = ArchivosRealPrestacion::where('dependencia', $clave)
                    ->where('mes', $mes)
                    ->where('ano', $ano)
                    ->where(function ($query) {
                        $query->whereNull('desempenio')->orWhere('desempenio', '');
                    })
                    ->orderBy('fecha_subida', 'desc')
                    ->get();

                $resultados[] = [
                    'dependencia' => $clave,
                    'nombre' => $nombreCompleto,
                    'archivos' => $archivos,
                ];
            }
        }

        return view('herramientas.real_prestacion_control', [
            'resultados' => $resultados,
            'mes' => $mes,
            'ano' => $ano,
        ]);
    }

    protected function getNombreCompleto($clave, $desempenio)
    {
        $desempenio = strtoupper($desempenio);
        return match ($clave) {
            'efme' => match ($desempenio) {
                'INIC' => 'Escuela Preuniversitaria Fray Mamerto Esquiu - Nivel Inicial',
                'PRIM' => 'Escuela Preuniversitaria Fray Mamerto Esquiu - Nivel Primario',
                'SECU' => 'Escuela Preuniversitaria Fray Mamerto Esquiu - Nivel Secundario',
                default => 'Escuela Preuniversitaria Fray Mamerto Esquiu',
            },
            'rect' => match ($desempenio) {
                'UNAI' => 'Unidad de Auditoria Interna',
                default => 'Personal Superior Rectorado',
            },
            default => $clave,
        };
    }
}