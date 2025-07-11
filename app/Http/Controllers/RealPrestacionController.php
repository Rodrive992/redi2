<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchivosRealPrestacion;
use App\Models\PlantaUnca;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RealPrestacionController extends Controller
{
    // Función para mostrar el formulario principal
    public function index()
    {
        return view('herramientas.real_prestacion_externo');
    }

    // Función para descargar la plantilla
    public function descargarPlantilla()
    {
        $user = Auth::user();
        $dependencia = $user->dependencia;
        $desempenio = $user->desempenio;

        // Obtener el nombre completo de la dependencia
        $dependenciaCompleta = $this->getDependenciaCompleta($dependencia, $desempenio);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REAL PRESTACIÓN');

        // Configuración básica del documento
        $this->configurarDocumento($sheet, $dependenciaCompleta);

        // Obtener datos de la planta
        $datosPlanta = $this->obtenerDatosPlanta($dependencia, $desempenio);

        // Llenar los datos en el Excel
        $this->llenarDatosExcel($sheet, $datosPlanta);

        // Crear hojas adicionales
        $this->crearHojaCodigos($spreadsheet);
        $this->crearHojaObservaciones($spreadsheet);

        // Generar nombre del archivo
        $mesActual = date('m');
        $anoActual = date('Y');
        $nombreArchivo = $dependenciaCompleta . ' Real Prestación ' . $mesActual . '_' . $anoActual . '.xlsx';

        // Descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Función para subir el archivo
    public function subirArchivo(Request $request)
    {
        $request->validate([
            'mes' => 'required|string|size:2',
            'ano' => 'required|integer|digits:4',
            'archivo_real_prestacion' => 'required|file|mimes:xlsx,xls|max:10240' // 10MB máximo
        ]);

        try {
            $user = Auth::user();
            $file = $request->file('archivo_real_prestacion');

            // Generar nombre único para el archivo
            $fileName = 'real_prestacion_' . $user->dependencia . '_' . $request->mes . '_' . $request->ano . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('real_prestaciones', $fileName, 'public');

            // Guardar en la base de datos
            $archivo = new ArchivosRealPrestacion();
            $archivo->nombre_archivo = $file->getClientOriginalName();
            $archivo->ruta_archivo = $filePath;
            $archivo->tipo_archivo = $file->getClientOriginalExtension();
            $archivo->tamano_archivo = $file->getSize();
            $archivo->fecha_subida = now();
            $archivo->dependencia = $user->dependencia;
            $archivo->mes = $request->mes;
            $archivo->ano = $request->ano;
            $archivo->usuario_envio = $user->name   ;
            $archivo->estado = 'pendiente';
            $archivo->save();

            return back()->with('success', 'El archivo se ha subido correctamente y está pendiente de aprobación.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
        }
    }

    // Métodos privados auxiliares

    private function getDependenciaCompleta($dependencia, $desempenio)
    {
        $dependencias = [
            'fexa' => 'Facultad de Ciencias Exactas y Naturales',
            'fhum' => 'Facultad de Humanidades',
            'ftec' => 'Facultad de Tecnología',
            'fder' => 'Facultad de Derecho',
            'fagr' => 'Facultad de Ciencias Agrarias',
            'feco' => 'Facultad de Ciencias Económicas y Administración',
            'earq' => 'Escuela de Arqueología',
            'efme' => 'Escuela Preuniversitaria Fray Mamerto Esquiú',
            'fsal' => 'Facultad de Ciencias de la Salud',
            'secf' => 'Secretaría Económico Financiera y Rectorado',
            'sgrl' => 'Secretaría General',
            'sbya' => 'Secretaría de Bienestar y Asuntos Estudiantiles',
            'enet' => 'Escuela Preuniversitaria ENET N°1',
            'rect' => 'Rectorado',
            'sext' => 'Secretaria de Extensión',
            'srii' => 'Secretaria de Relaciones Interinstitucionales e Internacionales',
            'siyp' => 'Secretaria de Investigacion y Posgrado',
            'saca' => 'Secretaria Academica'
        ];

        $desempenios = [
            'INIC' => ' - NIVEL INICIAL',
            'SECU' => ' - NIVEL SECUNDARIO',
            'PRIM' => ' - NIVEL PRIMARIO',
            'dgom' => ' - Dirección general de Obras y Mantenimiento',
            'unai' => ' - Unidad de Auditoria Interna'
        ];

        $nombre = $dependencias[$dependencia] ?? 'Dependencia Desconocida';

        if (!empty($desempenio)) {
            $nombre .= $desempenios[$desempenio] ?? '';
        }

        return $nombre;
    }

    private function configurarDocumento($sheet, $dependenciaCompleta)
    {
        // Configuración de página
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        // Margenes
        $sheet->getPageMargins()
            ->setTop(0.5)
            ->setRight(0.5)
            ->setLeft(0.5)
            ->setBottom(0.5);

        // Encabezado
        $sheet->mergeCells('A1:AK1');
        $sheet->setCellValue('A1', 'CERTIFICACIÓN DE REAL PRESTACIÓN DE SERVICIOS - ' . $dependenciaCompleta);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Cabeceras de columnas
        $cabeceras = [
            'A2' => 'NºCargo',
            'B2' => 'Nombre',
            'C2' => 'Cargo',
            'D2' => 'Caracter',
            'E2' => 'Dedicacion',
            'F2' => 'Licencia',
            'G2' => 'Carga Horaria'
        ];

        // Días del mes
        for ($i = 1; $i <= 31; $i++) {
            $columna = $this->numberToLetter(7 + $i);
            $cabeceras[$columna . '2'] = $i;
        }

        foreach ($cabeceras as $celda => $valor) {
            $sheet->setCellValue($celda, $valor);
        }

        // Estilo para cabeceras
        $sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->getFont()->setBold(true);
        $sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Congelar primera fila
        $sheet->freezePane('A3');

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        for ($i = 8; $i <= 38; $i++) {
            $columna = $this->numberToLetter($i);
            $sheet->getColumnDimension($columna)->setWidth(5);
        }
    }

    private function obtenerDatosPlanta($dependencia, $desempenio)
    {
        $planta = new PlantaUnca();

        $query = $planta->newQuery();

        if ($dependencia === 'rect') {
            if ($desempenio === 'unai') {
                $query->where('desempenio', 'unai')
                    ->where('caracter', '!=', 'DEXT');
            } else {
                $query->whereIn('legajo', [888, 1486, 3822, 4444, 1907, 4685, 2149, 5445, 232])
                    ->where('escalafon', 'Superior')
                    ->where('caracter', '!=', 'DEXT');
            }
        } else {
            $query->where('dependencia', $dependencia);

            if ($dependencia === 'srii') {
                $query->orWhere('dependencia', 'ssri');
            }

            if ($dependencia === 'secf') {
                $legajosEspeciales = [1834, 2456, 2473, 2731, 2812, 3125, 3531, 3534, 3790, 4319, 
                                    4673, 4679, 4685, 4801, 4939, 5009, 5530, 5160];
                $query->orWhere(function($q) use ($legajosEspeciales) {
                    $q->whereIn('legajo', $legajosEspeciales)
                      ->where(function($q2) {
                          $q2->where('dependencia', 'rect')
                             ->orWhere('dependencia', 'vrect');
                      });
                });
            }

            $query->where('caracter', '!=', 'DEXT');

            if ($dependencia === 'efme') {
                $query->where('desempenio', $desempenio);
            } elseif ($dependencia === 'sgrl') {
                if ($desempenio === null) {
                    $query->where(function($q) {
                        $q->whereNull('desempenio')
                          ->orWhere('dependencia', 'sslt');
                    });
                } else {
                    $query->where('desempenio', $desempenio);
                }
            }
        }

        return $query->orderByRaw("CASE WHEN dedicacion = 'nodocente' THEN 1 ELSE 0 END")
                    ->orderBy('nombre')
                    ->get();
    }

    private function llenarDatosExcel($sheet, $datosPlanta)
    {
        $rowIndex = 3;

        foreach ($datosPlanta as $row) {
            $sheet->setCellValue('A' . $rowIndex, $row->nro_cargo);
            $sheet->setCellValue('B' . $rowIndex, $row->nombre);
            $sheet->setCellValue('C' . $rowIndex, $row->cargo);
            $sheet->setCellValue('D' . $rowIndex, $row->caracter);
            $sheet->setCellValue('E' . $rowIndex, $row->dedicacion);
            $sheet->setCellValue('F' . $rowIndex, $row->licencia);
            $sheet->setCellValue('G' . $rowIndex, $row->hs . ' Horas');

            $rowIndex++;
        }

        // Aplicar bordes
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A2:' . $lastColumn . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    private function crearHojaCodigos($spreadsheet)
    {
        $sheetCodigos = $spreadsheet->createSheet();
        $sheetCodigos->setTitle('CÓDIGOS');

        $sheetCodigos->mergeCells('A1:B1');
        $sheetCodigos->setCellValue('A1', 'Códigos de causas de ausentismo');
        $sheetCodigos->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheetCodigos->getStyle('A1')->getFont()->setBold(true);

        $codigosCausas = [
            ['01', 'Licencia médica corto tratamiento'],
            ['02', 'Licencia médica largo tratamiento'],
            // ... (todos los códigos que tenías en tu ejemplo)
            ['49', 'Permiso deportivo gremial'],
        ];

        $row = 2;
        foreach ($codigosCausas as $codigoCausa) {
            $sheetCodigos->setCellValue('A' . $row, $codigoCausa[0]);
            $sheetCodigos->setCellValue('B' . $row, $codigoCausa[1]);
            $row++;
        }

        $sheetCodigos->getColumnDimension('A')->setWidth(10);
        $sheetCodigos->getColumnDimension('B')->setWidth(80);

        // Aplicar bordes
        $lastRow = $sheetCodigos->getHighestRow();
        $sheetCodigos->getStyle('A2:B' . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    private function crearHojaObservaciones($spreadsheet)
    {
        $sheetObs = $spreadsheet->createSheet();
        $sheetObs->setTitle('OBSERVACIONES');

        $sheetObs->mergeCells('A1:C1');
        $sheetObs->setCellValue('A1', 'Agregar observación');
        $sheetObs->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheetObs->getStyle('A1')->getFont()->setBold(true);

        $sheetObs->setCellValue('A2', 'Nombre');
        $sheetObs->setCellValue('B2', 'Cargo');
        $sheetObs->setCellValue('C2', 'Observación');

        $sheetObs->getColumnDimension('A')->setWidth(25);
        $sheetObs->getColumnDimension('B')->setWidth(25);
        $sheetObs->getColumnDimension('C')->setWidth(25);

        // Aplicar bordes
        $sheetObs->getStyle('A2:C2')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    private function numberToLetter($num)
    {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return $this->numberToLetter($num2) . $letter;
        } else {
            return $letter;
        }
    }
}