<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class CargarBaseUncaController extends Controller
{
    public function cargar(Request $request)
    {
        try {
            set_time_limit(300);
            ini_set('memory_limit', '256M');
            date_default_timezone_set('America/Argentina/Buenos_Aires');

            // Validación del archivo
            $request->validate([
                'csvFile' => 'required|file|mimes:csv,txt|max:10240', // 10MB máximo
            ]);

            $file = $request->file('csvFile');

            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir el archivo',
                ]);
            }

            $filePath = $file->getRealPath();

            if (!is_readable($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede leer el archivo subido',
                ]);
            }

            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo abrir el archivo CSV',
                ]);
            }

            $firstLine = fgetcsv($handle, 0, '|', '"');
            if ($firstLine === false || count($firstLine) < 44) {
                fclose($handle);
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo no tiene el formato esperado (mínimo 44 columnas)',
                ]);
            }

            $nombreTabla = 'planta_' . date('m') . '_' . date('Y');

            // Crear tabla si no existe
            DB::statement("
                CREATE TABLE IF NOT EXISTS `$nombreTabla` (
                    id_cargo INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    legajo VARCHAR(50) DEFAULT NULL,
                    nombre VARCHAR(100) DEFAULT NULL,
                    dni VARCHAR(20) DEFAULT NULL,
                    fecha_ingreso VARCHAR(20) DEFAULT NULL,
                    dependencia VARCHAR(100) DEFAULT NULL,
                    dependencia_comp VARCHAR(100) DEFAULT NULL,
                    escalafon VARCHAR(50) DEFAULT NULL,
                    agrupamiento VARCHAR(50) DEFAULT NULL,
                    subrogancia VARCHAR(50) DEFAULT NULL,
                    cargo VARCHAR(100) DEFAULT NULL,
                    nro_cargo VARCHAR(20) DEFAULT NULL,
                    caracter VARCHAR(50) DEFAULT NULL,
                    dedicacion VARCHAR(50) DEFAULT NULL,
                    alta_cargo VARCHAR(20) DEFAULT NULL,
                    vencimiento_cargo DATE DEFAULT NULL,
                    vencimiento_cargo1 DATE DEFAULT NULL,
                    hs INT(11) DEFAULT NULL,
                    licencia VARCHAR(100) DEFAULT NULL,
                    desempenio VARCHAR(100) DEFAULT NULL,
                    estado_baja VARCHAR(50) DEFAULT NULL,
                    fecha_observ DATE DEFAULT NULL,
                    estado_observ VARCHAR(50) DEFAULT NULL,
                    puntaje INT(11) DEFAULT NULL,
                    alta_licencia DATE DEFAULT NULL,
                    baja_licencia DATE DEFAULT NULL,
                    KEY idx_legajo (legajo)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            DB::statement("TRUNCATE TABLE `$nombreTabla`");

            $lineNumber = 1;
            $insertedRows = 0;
            $errors = [];

            $insertSQL = "
                INSERT INTO `$nombreTabla` (
                    legajo, nombre, dni, fecha_ingreso, dependencia, 
                    dependencia_comp, escalafon, agrupamiento, subrogancia, nro_cargo,
                    cargo, caracter, dedicacion, alta_cargo, vencimiento_cargo, 
                    vencimiento_cargo1, hs, licencia, desempenio, estado_baja, puntaje, 
                    alta_licencia, baja_licencia
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
            ";

            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare($insertSQL);

            while (($data = fgetcsv($handle, 0, '|')) !== false) {
                $lineNumber++;

                if (count($data) < 44) {
                    $errors[] = "Línea $lineNumber: columnas insuficientes";
                    continue;
                }

                $cleanData = array_map(fn($item) => trim(str_replace('""', '', $item)), $data);

                $legajo = $cleanData[0];
                $nombre = $cleanData[1];
                $dni = preg_replace('/[^0-9]/', '', $cleanData[2]);
                $fecha_ingreso = $this->formatDateForDB($cleanData[8]);
                $alta_cargo = $this->formatDateForDB($cleanData[26]);
                $dependencia = $cleanData[12];
                $dependencia_comp = $cleanData[13] . ' - ' . $cleanData[13];
                $escalafon = $cleanData[16];
                $agrupamiento = $cleanData[18];
                $subrogancia = $cleanData[21];
                $cargo = $cleanData[22] . (!empty($cleanData[21]) ? ' (' . $cleanData[21] . ')' : '');
                $nro_cargo = $cleanData[20];
                $caracter = $this->convertCodeToText($cleanData[23]);
                $dedicacion = $this->convertCodeToText($cleanData[25]);
                $vencimiento_cargo = $this->formatDateForDB($cleanData[27]);
                $vencimiento_cargo1 = $this->formatDateForDB($cleanData[27]);
                $hs = (int)str_replace(',00', '', $cleanData[30]);
                $licencia = (strcasecmp(trim($cleanData[41]), 'No remunerada') === 0) ? $cleanData[41] : null;
                $desempenio = $cleanData[43];

                // Campos faltantes inicializados como null o vacíos
                $estado_baja = null;
                $puntaje = null;
                $alta_licencia = null;
                $baja_licencia = null;

                if (empty($legajo)) {
                    $errors[] = "Línea $lineNumber: Legajo vacío";
                    continue;
                }

                try {
                    $stmt->execute([
                        $legajo, $nombre, $dni, $fecha_ingreso, $dependencia,
                        $dependencia_comp, $escalafon, $agrupamiento, $subrogancia, $nro_cargo,
                        $cargo, $caracter, $dedicacion, $alta_cargo, $vencimiento_cargo,
                        $vencimiento_cargo1, $hs, $licencia, $desempenio, 
                        $estado_baja, $puntaje, $alta_licencia, $baja_licencia
                    ]);
                    $insertedRows++;
                } catch (Exception $e) {
                    $errors[] = "Línea $lineNumber: " . $e->getMessage();
                }
            }

            fclose($handle);

            return response()->json([
                'success' => true,
                'message' => "Archivo procesado correctamente. Tabla: $nombreTabla",
                'inserted_rows' => $insertedRows,
                'total_lines' => $lineNumber,
                'error_count' => count($errors),
                'sample_errors' => array_slice($errors, 0, 5),
                'table_name' => $nombreTabla,
                'file_info' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'lines_processed' => $lineNumber
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error en carga de Unca: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function convertCodeToText($code)
    {
        $map = [
            'PERM' => 'PERMANENTE', 'CONS' => 'CONSULTO', 'CONC' => 'CONCURSADO',
            'CONT' => 'CONTRATADO', 'INTE' => 'INTERINO', 'INTN' => 'NODOCENTE INTERINO',
            'ORDI' => 'ORDINARIO', 'REGU' => 'REGULAR', 'SUPL' => 'SUPLENTE',
            'TITU' => 'TITULAR', 'ADHO' => 'ADHONOREM', 'EXCL' => 'EXCLUSIVO',
            'SIMP' => 'SIMPLE', 'SEMI' => 'SEMIEXCLUSIVO', 'NODO' => 'NODOCENTE'
        ];
        return $map[strtoupper(trim($code))] ?? $code;
    }

    private function formatDateForDB($date)
    {
        if (empty($date) || strtoupper($date) === 'NULL' || $date === '""') return null;
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date, $m)) {
            return "$m[3]-$m[2]-$m[1]";
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        return null;
    }
}