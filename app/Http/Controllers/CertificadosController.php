<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantaUnca;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CertificadosController extends Controller
{
    public function certificados(Request $request)
    {
        $tipo_certificado = $request->input('tipo_certificado');
        $dni = $request->input('dni');
        $empleado = null;

        if ($dni) {
            // Buscar en todas las bases de datos
            $empleado = PlantaUnca::where('dni', $dni)->first();
           
        }

        return view('herramientas.certificados', [
            'tipo_certificado' => $tipo_certificado,
            'dni' => $dni,
            'empleado' => $empleado
        ]);
    }

   public function exportarCertificados(Request $request)
{
    $request->validate([
        'nombre' => 'required',
        'tipo_certificado' => 'required|in:horarios,cargos,sueldos',
        'dni' => 'required|numeric',
        'fecha_actual' => 'required',
        'cargo' => 'required',
        'dependencia' => 'required',
        'caracter' => 'required',
        'dedicacion' => 'required',
        'entidad_destinataria' => 'required'
    ]);
    
    $data = $request->all();

    // Crear nuevo objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Certificado');

    // Configuración de la página
    $sheet->getPageSetup()
        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_A4)
        ->setFitToWidth(1)
        ->setFitToHeight(0);

    // Margenes
    $sheet->getPageMargins()
        ->setTop(0.8)
        ->setRight(0.8)
        ->setLeft(0.8)
        ->setBottom(0.8)
        ->setHeader(0.3)
        ->setFooter(0.3);

    // Estilo de borde externo
    $borderStyle = [
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['rgb' => '000000'],
            ]
        ]
    ];

    // Estilos
    $headerStyle = [
        'font' => [
            'bold' => true,
            'size' => 11,
            'name' => 'Arial'
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ]
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F2F2F2']
        ]
    ];

    $titleStyle = [
        'font' => [
            'bold' => true,
            'size' => 14,
            'name' => 'Arial'
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ]
    ];

    $certificateTextStyle = [
        'font' => [
            'bold' => true,
            'size' => 11,
            'name' => 'Arial'
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ]
        ]
    ];

    $normalStyle = [
        'font' => [
            'bold' => true,
            'size' => 11,
            'name' => 'Arial'
        ],
        'alignment' => [
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ]
        ]
    ];

    $signatureStyle = [
        'font' => [
            'bold' => true,
            'size' => 11,
            'name' => 'Arial',
            'italic' => true
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ]
        ]
    ];

    // Agregar logo más pequeño
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo UNCA');
    $drawing->setPath(public_path('images/logo_unca.jpg'));
    $drawing->setHeight(45); // Tamaño reducido
    $drawing->setCoordinates('A1');
    $drawing->setWorksheet($sheet);

    // Cabecera del certificado (filas 1 y 2 fusionadas)
    $sheet->mergeCells('A1:E2');
    $sheet->setCellValue('A1', 'UNIVERSIDAD NACIONAL DE CATAMARCA' . "\n" . 'DIRECCIÓN GENERAL DE PERSONAL');
    $sheet->getStyle('A1:E2')->applyFromArray($titleStyle);
    $sheet->getStyle('A1:E2')->getAlignment()->setWrapText(true);
    $sheet->getRowDimension(1)->setRowHeight(25);
    $sheet->getRowDimension(2)->setRowHeight(25);

    // Fila 3 vacía
    $sheet->mergeCells('A3:E3');
    $sheet->setCellValue('A3', '');
    $sheet->getRowDimension(3)->setRowHeight(10);

    // Texto del certificado (comienza en fila 4)
    $certificateText = "Quien suscribe, Directora General de Personal de la Universidad Nacional de Catamarca, ";
    $certificateText .= "CERTIFICA que el/la agente {$data['nombre']} - DNI N° {$data['dni']}, ";
    $certificateText .= "reviste la siguiente situación en esta institución al día de la fecha: {$data['fecha_actual']}\n";

    $sheet->mergeCells('A4:E6');
    $sheet->setCellValue('A4', $certificateText);
    $sheet->getStyle('A4:E6')->applyFromArray($certificateTextStyle);
    $sheet->getRowDimension(4)->setRowHeight(20);
    $sheet->getRowDimension(5)->setRowHeight(20);
    $sheet->getRowDimension(6)->setRowHeight(20);

    // Tabla de datos principal
    $currentRow = 7;

    // Encabezado de la tabla
    $sheet->setCellValue('A'.$currentRow, 'CARGO');
    $sheet->setCellValue('B'.$currentRow, 'DEPENDENCIA');
    $sheet->mergeCells('C'.$currentRow.':D'.$currentRow);
    $sheet->setCellValue('C'.$currentRow, 'CARÁCTER');
    $sheet->setCellValue('E'.$currentRow, 'DEDICACIÓN');
    $sheet->getStyle('A'.$currentRow.':E'.$currentRow)->applyFromArray($headerStyle);
    $sheet->getRowDimension($currentRow)->setRowHeight(25);
    $currentRow++;

    // Datos de la tabla
    $sheet->setCellValue('A'.$currentRow, $data['cargo']);
    $sheet->setCellValue('B'.$currentRow, $data['dependencia']);
    $sheet->mergeCells('C'.$currentRow.':D'.$currentRow);
    $sheet->setCellValue('C'.$currentRow, $data['caracter']);
    $sheet->setCellValue('E'.$currentRow, $data['dedicacion']);
    $sheet->getStyle('A'.$currentRow.':E'.$currentRow)->applyFromArray($normalStyle);
    $sheet->getRowDimension($currentRow)->setRowHeight(30);
    $currentRow++;

    // Espacio
    $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
    $sheet->setCellValue('A'.$currentRow, '');
    $sheet->getRowDimension($currentRow)->setRowHeight(10);
    $currentRow++;

    // Sección específica según tipo de certificado
    if ($data['tipo_certificado'] == 'horarios') {
        $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
        $sheet->setCellValue('A'.$currentRow, 'HORARIOS DE TRABAJO');
        $sheet->getStyle('A'.$currentRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ]
            ]
        ]);
        $currentRow++;

        // Encabezado de horarios
        $sheet->setCellValue('A'.$currentRow, 'LUNES');
        $sheet->setCellValue('B'.$currentRow, 'MARTES');
        $sheet->setCellValue('C'.$currentRow, 'MIÉRCOLES');
        $sheet->setCellValue('D'.$currentRow, 'JUEVES');
        $sheet->setCellValue('E'.$currentRow, 'VIERNES');
        $sheet->getStyle('A'.$currentRow.':E'.$currentRow)->applyFromArray($headerStyle);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);
        $currentRow++;

        // Horarios
        $sheet->setCellValue('A'.$currentRow, $data['horario_lunes'] ?? '-');
        $sheet->setCellValue('B'.$currentRow, $data['horario_martes'] ?? '-');
        $sheet->setCellValue('C'.$currentRow, $data['horario_miercoles'] ?? '-');
        $sheet->setCellValue('D'.$currentRow, $data['horario_jueves'] ?? '-');
        $sheet->setCellValue('E'.$currentRow, $data['horario_viernes'] ?? '-');
        $sheet->getStyle('A'.$currentRow.':E'.$currentRow)->applyFromArray($normalStyle);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);
        $currentRow++;

        // Espacio
        $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
        $sheet->setCellValue('A'.$currentRow, '');
        $sheet->getRowDimension($currentRow)->setRowHeight(10);
        $currentRow++;
    }

    // Antigüedad
    if (!empty($data['antiguedad'])) {
        $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
        $sheet->setCellValue('A'.$currentRow, 'ANTIGÜEDAD EN EL CARGO: ' . $data['antiguedad']);
        $sheet->getStyle('A'.$currentRow)->applyFromArray($normalStyle);
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $currentRow++;
    }

    // Sección para sueldos
    if ($data['tipo_certificado'] == 'sueldos') {
        $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
        $sheet->setCellValue('A'.$currentRow, 'SUELDO BRUTO: $' . number_format((float)($data['sueldo_bruto'] ?? 0), 2, ',', '.'));
        $sheet->getStyle('A'.$currentRow)->applyFromArray($normalStyle);
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $currentRow++;

        $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
        $sheet->setCellValue('A'.$currentRow, 'SUELDO NETO: $' . number_format((float)($data['sueldo_neto'] ?? 0), 2, ',', '.'));
        $sheet->getStyle('A'.$currentRow)->applyFromArray($normalStyle);
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $currentRow++;

        if (!empty($data['observaciones'])) {
            $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
            $sheet->setCellValue('A'.$currentRow, 'OBSERVACIONES: ' . $data['observaciones']);
            $sheet->getStyle('A'.$currentRow)->applyFromArray($normalStyle);
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $currentRow++;
        }
    }

    // Entidad destinataria
    $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
    $sheet->setCellValue('A'.$currentRow, 'SE EXPIDE EL PRESENTE A SOLICITUD DEL INTERESADO/A Y PARA SER PRESENTADO ANTE: ' . $data['entidad_destinataria']);
    $sheet->getStyle('A'.$currentRow)->applyFromArray($normalStyle);
    $sheet->getRowDimension($currentRow)->setRowHeight(20);
    $currentRow++;

    // Espacio para firma
    $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
    $sheet->setCellValue('A'.$currentRow, '');
    $sheet->getRowDimension($currentRow)->setRowHeight(30);
    $currentRow++;

    // Firma
    $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
    $sheet->setCellValue('A'.$currentRow, '_________________________');
    $sheet->getStyle('A'.$currentRow)->applyFromArray($signatureStyle);
    $sheet->getRowDimension($currentRow)->setRowHeight(20);
    $currentRow++;

    $sheet->mergeCells('A'.$currentRow.':E'.$currentRow);
    $sheet->setCellValue('A'.$currentRow, 'Directora General de Personal');
    $sheet->getStyle('A'.$currentRow)->applyFromArray($signatureStyle);
    $sheet->getRowDimension($currentRow)->setRowHeight(20);

    // Aplicar borde externo a todo el contenido
    $sheet->getStyle('A1:E'.($currentRow))->applyFromArray($borderStyle);

    // Configuración de anchos de columnas
    $sheet->getColumnDimension('A')->setWidth(35);  // Cargo
    $sheet->getColumnDimension('B')->setWidth(35);  // Dependencia
    $sheet->getColumnDimension('C')->setWidth(15);  // Carácter (parte fusionada)
    $sheet->getColumnDimension('D')->setWidth(15);  // Parte fusionada de Carácter
    $sheet->getColumnDimension('E')->setWidth(25);  // Dedicación/Horario viernes

    // Generar el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Certificado_' . $data['tipo_certificado'] . '_' . $data['dni'] . '_' . date('Ymd_His') . '.xlsx';

    return response()->streamDownload(function() use ($writer) {
        $writer->save('php://output');
    }, $fileName);
}
}