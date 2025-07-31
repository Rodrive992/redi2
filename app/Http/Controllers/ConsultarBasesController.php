<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantaAdmProvincia;
use App\Models\PlantaEduProvincia;
use App\Models\PlantaUnca;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ConsultarBasesController extends Controller
{
    public function index(Request $request)
    {
        $base = $request->input('base', '');
        $search = $request->input('search', '');
        $results = null;

        if ($base) {
            switch ($base) {
                case 'educacion':
                    $query = PlantaEduProvincia::query();
                    break;
                case 'administracion':
                    $query = PlantaAdmProvincia::query();
                    break;
                case 'unca':
                    $query = PlantaUnca::query();
                    break;
                default:
                    return redirect()->route('herramientas.consultar_bases');
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%")
                      ->orWhere('dni', 'like', "%$search%")
                      ->orWhere('legajo', 'like', "%$search%")
                      ->orWhere('dependencia', 'like', "%$search%")
                      ->orWhere('cargo', 'like', "%$search%");
                });
            }

            $results = $query->paginate(25);
        }

        return view('herramientas.consultar_bases', compact('results', 'base', 'search'));
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'base' => 'required|in:educacion,administracion,unca'
        ]);

        $base = $request->input('base');
        $search = $request->input('search', '');

        switch ($base) {
            case 'educacion':
                $query = PlantaEduProvincia::query();
                break;
            case 'administracion':
                $query = PlantaAdmProvincia::query();
                break;
            case 'unca':
                $query = PlantaUnca::query();
                break;
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('dni', 'like', "%$search%")
                  ->orWhere('legajo', 'like', "%$search%")
                  ->orWhere('dependencia', 'like', "%$search%")
                  ->orWhere('cargo', 'like', "%$search%");
            });
        }

        $results = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'Legajo', 'Nombre', 'DNI', 'Ingreso', 'Dependencia', 
            'Desempeño', 'Cargo', 'Escalafón', 'Agrupamiento',
            'Subrogancia', 'Caracter', 'Dedicación', 'Alta Cargo',
            'Vencimiento', 'Puntaje', 'Horas', 'Licencia'
        ];

        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        // Datos
        foreach ($results as $row => $item) {
            $data = [
                $item->legajo,
                $item->nombre,
                $item->dni,
                $item->fecha_ingreso,
                $item->dependencia,
                $item->desempenio,
                $item->cargo,
                $item->escalafon,
                $item->agrupamiento,
                $item->subrogancia,
                $item->caracter,
                $item->dedicacion,
                $item->alta_cargo,
                $item->vencimiento_cargo,
                $item->puntaje,
                $item->hs,
                $item->licencia
            ];

            foreach ($data as $col => $value) {
                $sheet->setCellValueByColumnAndRow($col + 1, $row + 2, $value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'exportacion_' . $base . '_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }
}