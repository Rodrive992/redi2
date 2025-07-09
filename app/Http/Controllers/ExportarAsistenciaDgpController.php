<?php

namespace App\Http\Controllers;

use App\Models\AsignacionLegajos;
use App\Models\AsignacionRelojes;
use App\Models\AsistenciaReloj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportarAsistenciaDgpController extends Controller
{
    public function exportar(Request $request)
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $request->validate([
            'dependencia' => 'required',
            'desempenio' => 'nullable',
            'desde' => 'required|date',
            'hasta' => 'required|date',
            'nombre_legajo' => 'nullable|string'
        ]);

        $datos = $this->procesarAsistencia($request);

        $usuario = Auth::user();
        $cuil = $usuario->cuil ?? 'Desconocido';

        $dep = $request->dependencia;
        $des = $request->desempenio;

        $dependenciaCompleta = match ($dep) {
            'efme' => match ($des) {
                'prim' => 'Escuela Fray Mamerto Esquiú - Primaria',
                'secu' => 'Escuela Fray Mamerto Esquiú - Secundaria',
                'inic' => 'Escuela Fray Mamerto Esquiú - Inicial',
                default => 'Escuela Fray Mamerto Esquiú'
            },
            'sgrl' => $des === 'dgom' ? 'Secretaría General - Dirección General de Obras' : 'Secretaría General',
            'sext' => 'Secretaría de Extensión',
            'dgp' => 'Dirección General de Personal',
            'siyp' => 'Secretaría de Investigación y Posgrado',
            'srii' => 'Secretaría de Relaciones Interinstitucionales',
            'enet' => 'Escuela ENET N°1',
            'sbya' => 'Secretaría de Bienestar y Asuntos Estudiantiles',
            'saca' => 'Secretaría de Asuntos Académicos',
            default => 'Dependencia Desconocida'
        };

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Informe de Asistencia');

        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $ultimaColumna = Coordinate::stringFromColumnIndex(count($datos['fechas']) + 2);

        // Estilos
        $tituloStyle = [
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']]
        ];

        $subtituloStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]
        ];

        $cabeceraStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        $contenidoStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        // Encabezados
        $sheet->setCellValue('A1', 'UNIVERSIDAD NACIONAL DE CATAMARCA - INFORME DE ASISTENCIA');
        $sheet->mergeCells("A1:{$ultimaColumna}1");
        $sheet->getStyle("A1:{$ultimaColumna}1")->applyFromArray($tituloStyle);

        $sheet->setCellValue('A2', strtoupper($dependenciaCompleta));
        $sheet->mergeCells("A2:{$ultimaColumna}2");
        $sheet->getStyle("A2:{$ultimaColumna}2")->applyFromArray($subtituloStyle);

        $sheet->setCellValue('A3', 'Período: ' . date('d/m/Y', strtotime($request->desde)) . ' al ' . date('d/m/Y', strtotime($request->hasta)));
        $sheet->mergeCells("A3:{$ultimaColumna}3");
        $sheet->getStyle("A3:{$ultimaColumna}3")->applyFromArray($subtituloStyle);

        $sheet->setCellValue('A4', 'Exportado por CUIL: ' . $cuil . ' - Fecha y hora: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells("A4:{$ultimaColumna}4");
        $sheet->getStyle("A4:{$ultimaColumna}4")->applyFromArray($subtituloStyle);

        // Cabeceras tabla
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col++, 5, 'Legajo');
        $sheet->setCellValueByColumnAndRow($col++, 5, 'Nombre');
        foreach ($datos['fechas'] as $fecha) {
            $sheet->setCellValueByColumnAndRow($col++, 5, Carbon::parse($fecha)->format('d/m'));
        }
        $sheet->getStyle("A5:{$ultimaColumna}5")->applyFromArray($cabeceraStyle);

        // Cuerpo tabla
        $fila = 6;
        foreach ($datos['asistencia'] as $legajo => $dias) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col++, $fila, $legajo);
            $sheet->setCellValueByColumnAndRow($col++, $fila, $datos['nombres'][$legajo] ?? '');
            foreach ($datos['fechas'] as $fecha) {
                $registros = $dias[$fecha] ?? [];
                $sheet->setCellValueByColumnAndRow($col++, $fila, count($registros) ? implode(' - ', $registros) : '-');
            }
            $fila++;
        }

        $sheet->getStyle("A6:{$ultimaColumna}" . ($fila - 1))->applyFromArray($contenidoStyle);

        for ($i = 1; $i <= count($datos['fechas']) + 2; $i++) {
            $colLetra = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetra)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = 'Asistencia_' . $dep . '_' . date('Ymd_His') . '.xlsx';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ]);
    }

    private function procesarAsistencia(Request $request)
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
                $q->where('nombre_legajo', 'like', '%'.$request->nombre_legajo.'%')
                  ->orWhere('legajo', 'like', '%'.$request->nombre_legajo.'%');
            });
        }

        $legajos = $legajos->get();
        $legajosFiltrados = $legajos->pluck('legajo')->toArray();
        $nombres = $legajos->pluck('nombre_legajo', 'legajo')->toArray();

        $relojes = AsignacionRelojes::where('dependencia_usuario', $request->dependencia);

        if ($request->dependencia === 'sgrl' && !$request->desempenio) {
            $relojes->where(function ($q) {
                $q->whereNull('desempenio_usuario')->orWhere('desempenio_usuario', '!=', 'dgom');
            });
        } elseif ($request->desempenio) {
            $relojes->where('desempenio_usuario', $request->desempenio);
        }

        $relojesFiltrados = $relojes->pluck('reloj')->toArray();

        $asistencia = AsistenciaReloj::query();

        if (!empty($legajosFiltrados)) {
            $asistencia->whereIn('legajo', $legajosFiltrados);
        }

        if (!empty($relojesFiltrados)) {
            $asistencia->whereIn('reloj', $relojesFiltrados);
        }

        if ($request->nombre_legajo) {
            $asistencia->where(function ($q) use ($request) {
                $q->where('nombre_apellido', 'like', '%'.$request->nombre_legajo.'%')
                  ->orWhere('legajo', 'like', '%'.$request->nombre_legajo.'%');
            });
        }

        $asistencia->whereBetween('fecha', [$request->desde, $request->hasta]);

        $registros = $asistencia->orderBy('legajo')->orderBy('fecha')->orderBy('registro')->get();

        $fechas = [];
        $desde = Carbon::parse($request->desde);
        $hasta = Carbon::parse($request->hasta);

        for ($fecha = $desde->copy(); $fecha <= $hasta; $fecha->addDay()) {
            $fechas[] = $fecha->format('Y-m-d');
        }

        $estructura = [];
        foreach ($legajosFiltrados as $legajo) {
            foreach ($fechas as $fecha) {
                $estructura[$legajo][$fecha] = [];
            }
        }

        foreach ($registros as $r) {
            $hora = substr($r->registro, 0, 5);
            $estructura[$r->legajo][$r->fecha][] = $hora;
        }

        foreach ($estructura as $legajo => $dias) {
            foreach ($dias as $fecha => $horas) {
                $horas = array_unique($horas);
                sort($horas);

                $filtrados = [];
                $ultimoMinutos = null;

                foreach ($horas as $hora) {
                    [$h, $m] = explode(':', $hora);
                    $minutosActual = $h * 60 + $m;

                    if (is_null($ultimoMinutos) || ($minutosActual - $ultimoMinutos) >= 40) {
                        $filtrados[] = $hora;
                        $ultimoMinutos = $minutosActual;
                    }
                }

                $estructura[$legajo][$fecha] = $filtrados;
            }
        }

        uksort($estructura, function ($a, $b) use ($nombres) {
            return strcmp($nombres[$a] ?? '', $nombres[$b] ?? '');
        });

        return [
            'fechas' => $fechas,
            'asistencia' => $estructura,
            'nombres' => $nombres
        ];
    }
}