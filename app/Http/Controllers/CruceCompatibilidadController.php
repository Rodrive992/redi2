<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantaAdmProvincia;
use App\Models\PlantaEduProvincia;
use App\Models\PlantaUnca;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Illuminate\Support\Facades\Auth;

class CruceCompatibilidadController extends Controller
{
    public function compatibilidad(Request $request)
    {
        $dni = $request->input('dni');
        $empleados = collect(); // Crear una colección vacía

        if ($dni) {
            // Buscar en todas las bases de datos
            $empleados = $empleados->merge(PlantaAdmProvincia::where('dni', $dni)->get());
            $empleados = $empleados->merge(PlantaEduProvincia::where('dni', $dni)->get());
            $empleados = $empleados->merge(PlantaUnca::where('dni', $dni)->get());

            // Ordenar por dependencia
            $empleados = $empleados->sortBy('dependencia');
        }

        return view('herramientas.compatibilidad', [
            'empleados' => $empleados,
            'dni' => $dni
        ]);
    }

    public function exportarCompatibilidad(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric'
        ]);

        $dni = $request->input('dni');
        $empleados = collect();

        // Buscar en todas las bases
        $empleados = $empleados->merge(PlantaAdmProvincia::where('dni', $dni)->get());
        $empleados = $empleados->merge(PlantaEduProvincia::where('dni', $dni)->get());
        $empleados = $empleados->merge(PlantaUnca::where('dni', $dni)->get());

        if ($empleados->isEmpty()) {
            return back()->with('error', 'No se encontraron registros para exportar');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Compatibilidad');

        // Configuración de página
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        // Margenes
        $sheet->getPageMargins()
            ->setTop(0.5)
            ->setRight(0.3)
            ->setLeft(0.3)
            ->setBottom(0.5);

        // Estilos generales
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ]
        ];

        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        // Cabecera del documento
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'DIRECCIÓN GENERAL DE PERSONAL - UNCA');
        $sheet->getStyle('A1')->applyFromArray($titleStyle);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'CRUCE DE BASES DE DATOS - UNCA, ADMINISTRACIÓN Y EDUCACIÓN PROVINCIAL');
        $sheet->getStyle('A2')->applyFromArray($titleStyle);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'FECHA: ' . date('d/m/Y') . ' - USUARIO: ' . Auth::user()->cuil);
        $sheet->getStyle('A3')->applyFromArray($titleStyle);

        // Información del empleado
        $primerEmpleado = $empleados->first();
        $sheet->mergeCells('A4:I4');
        $sheet->setCellValue('A4', $primerEmpleado->nombre . ' - DNI: ' . $primerEmpleado->dni);
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Encabezados de columnas
        $headers = [
            'A' => ['text' => 'DEPENDENCIA', 'width' => 35],
            'B' => ['text' => 'CARGO', 'width' => 35],
            'C' => ['text' => 'ALTA', 'width' => 12],
            'D' => ['text' => 'CARÁCTER', 'width' => 20],
            'E' => ['text' => 'DEDICACIÓN', 'width' => 15],
            'F' => ['text' => 'LICENCIA', 'width' => 20],
            'G' => ['text' => 'DESDE', 'width' => 12],
            'H' => ['text' => 'HASTA', 'width' => 12],
            'I' => ['text' => 'HS', 'width' => 8]
        ];

        foreach ($headers as $col => $data) {
            $sheet->setCellValue($col . '5', $data['text']);
            $sheet->getColumnDimension($col)->setWidth($data['width']);
        }
        $sheet->getStyle('A5:I5')->applyFromArray($headerStyle);
        $sheet->getRowDimension(5)->setRowHeight(25);

        // Datos de los empleados
        $row = 6;
        $totalHoras = 0;

        foreach ($empleados as $empleado) {
            // Sumar horas si no tiene licencia
            if (empty($empleado->licencia) || strtolower(trim($empleado->licencia)) === 'null') {
                $totalHoras += $empleado->hs;
            }

            $sheet->setCellValue('A' . $row, $empleado->dependencia);
            $sheet->setCellValue('B' . $row, $empleado->cargo);
            $sheet->setCellValue('C' . $row, $empleado->alta_cargo);
            $sheet->setCellValue('D' . $row, $empleado->caracter);
            $sheet->setCellValue('E' . $row, $empleado->dedicacion);
            $sheet->setCellValue('F' . $row, $empleado->licencia ?? '-');
            $sheet->setCellValue('G' . $row, $empleado->alta_licencia ?? '-');
            $sheet->setCellValue('H' . $row, $empleado->baja_licencia ?? '-');
            $sheet->setCellValue('I' . $row, $empleado->hs);

            // Alternar colores de fila para mejor lectura
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':I' . $row)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('E9E9E9');
            }

            $row++;
        }

        // Totales
        $sheet->setCellValue('H' . $row, 'SUBTOTAL HS:');
        $sheet->setCellValue('I' . $row, $totalHoras);
        $sheet->getStyle('H' . $row . ':I' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ]);

        $row++;
        $sheet->setCellValue('A' . $row, 'NOVEDAD:');
        $sheet->mergeCells('B' . $row . ':G' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        $row++;
        $sheet->setCellValue('H' . $row, 'TOTAL HS:');
        $sheet->setCellValue('I' . $row, $totalHoras);
        $sheet->getStyle('H' . $row . ':I' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9']
            ]
        ]);

        $row++;
        $sheet->setCellValue('A' . $row, 'OBSERVACIÓN:');
        $sheet->mergeCells('B' . $row . ':I' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        // Aplicar bordes a toda la tabla
        $sheet->getStyle('A1:I' . ($row))->applyFromArray($borderStyle);

        // Centrar contenido en columnas específicas
        $sheet->getStyle('C6:C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G6:I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Generar el archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Compatibilidad_' . $primerEmpleado->nombre . '_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }
}