<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use ZipArchive;

class CargarBaseEduProvController extends Controller
{
    public function cargar(Request $request)
    {
        try {
            set_time_limit(600);
            ini_set('memory_limit', '512M');
            date_default_timezone_set('America/Argentina/Buenos_Aires');

            $request->validate([
                'csvFile' => 'required|file|mimes:zip|max:51200'
            ]);

            $file = $request->file('csvFile');

            if (!$file->isValid()) {
                return response()->json(['success' => false, 'message' => 'Error al subir el archivo']);
            }

            $tempDir = storage_path('app/temp_edu_' . uniqid());
            mkdir($tempDir, 0777, true);

            $zip = new ZipArchive;
            if ($zip->open($file->getRealPath()) === TRUE) {
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                return response()->json(['success' => false, 'message' => 'Error al descomprimir el archivo ZIP']);
            }

            $csvFile = null;
            foreach (scandir($tempDir) as $fileName) {
                if (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) === 'csv') {
                    $csvFile = $tempDir . DIRECTORY_SEPARATOR . $fileName;
                    break;
                }
            }

            if (!$csvFile || !is_readable($csvFile)) {
                return response()->json(['success' => false, 'message' => 'No se encontró o no se puede leer el CSV']);
            }

            $insertedRows = 0;
            $errors = [];
            $lineNumber = 0;

            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("
                INSERT INTO planta_educacion_provincia 
                (nombre, dni, dependencia, cargo, puntaje, hs, alta_cargo, caracter, licencia, alta_licencia, baja_licencia) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if (($handle = fopen($csvFile, 'r')) !== false) {

                // Saltar las primeras 6 líneas
                for ($i = 0; $i < 6; $i++) {
                    fgets($handle);
                }

                while (($data = fgetcsv($handle, 0, ';', '"')) !== false) {
                    $lineNumber++;

                    if (count($data) < 14) {
                        $errors[] = "Línea $lineNumber: columnas insuficientes";
                        continue;
                    }

                    try {
                        $nombre = $this->cleanText(trim($data[0]) . ' ' . trim($data[1]));
                        $dni = $this->cleanText($data[2]);
                        $dependencia = $this->cleanText($data[5]);
                        $cargo = $this->cleanText($data[6]);

                        if (empty($nombre) || empty($dni) || empty($dependencia) || empty($cargo)) {
                            throw new Exception("Faltan campos obligatorios (nombre, dni, dependencia, cargo)");
                        }

                        $puntaje = is_numeric($data[7]) ? (int)$data[7] : null;
                        $hs = is_numeric($data[8]) ? (int)$data[8] : null;
                        $alta_cargo = $this->formatDateForDB($data[9]);
                        $caracter = $this->cleanText($data[10]);
                        $licencia = $this->cleanText($data[11]);
                        $alta_licencia = $this->formatDateForDB($data[12]);
                        $baja_licencia = $this->formatDateForDB($data[13]);

                        $stmt->execute([
                            $nombre, $dni, $dependencia, $cargo, $puntaje, $hs,
                            $alta_cargo, $caracter, $licencia, $alta_licencia, $baja_licencia
                        ]);

                        $insertedRows++;

                    } catch (Exception $e) {
                        $errors[] = "Línea $lineNumber: " . $e->getMessage();
                        Log::error("Error línea $lineNumber: " . $e->getMessage());
                    }
                }

                fclose($handle);

            } else {
                return response()->json(['success' => false, 'message' => 'No se pudo abrir el archivo CSV']);
            }

            array_map('unlink', glob($tempDir . DIRECTORY_SEPARATOR . '*'));
            rmdir($tempDir);

            return response()->json([
                'success' => $insertedRows > 0,
                'message' => $insertedRows > 0 ? 'Archivo procesado con éxito' : 'No se insertaron registros',
                'inserted_rows' => $insertedRows,
                'total_lines_processed' => $lineNumber,
                'error_count' => count($errors),
                'sample_errors' => array_slice($errors, 0, 5)
            ]);

        } catch (Exception $e) {
            Log::error('Error en carga de Educación Provincia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function cleanText($text)
    {
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $text);
        return trim($text);
    }

    private function formatDateForDB($date)
    {
        if (empty($date) || strtoupper($date) === 'NULL' || $date === '""') return null;
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        return null;
    }
}