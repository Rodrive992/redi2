<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CargarBaseRelojesController extends Controller
{
    public function cargar(Request $request)
    {
        try {
            set_time_limit(300);
            ini_set('memory_limit', '256M');
            date_default_timezone_set('America/Argentina/Buenos_Aires');

            // Validación del archivo
            $request->validate([
                'csvFile' => 'required|file|mimes:csv,txt|max:10240', // Máximo 10MB
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

            $insertedRows = 0;
            $errors = [];
            $lineNumber = 7;

            $pdo = DB::connection()->getPdo();

            $stmt = $pdo->prepare("
                INSERT INTO asistencia_reloj 
                (reloj, legajo, nombre_apellido, dependencia, fecha, registro) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            if (($handle = fopen($filePath, 'r')) !== false) {
                // Saltar 6 líneas de cabecera
                for ($i = 0; $i < 5; $i++) {
                    fgetcsv($handle, 0, ';');
                }

                // Línea 7 es el encabezado, la descartamos
                fgetcsv($handle, 0, ';');

                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    $lineNumber++;

                    try {
                        if (count($data) < 8 || empty($data[0])) {
                            $errors[] = "Línea $lineNumber: No tiene suficientes columnas";
                            continue;
                        }

                        $reloj = mb_convert_encoding(trim($data[0]), 'UTF-8');
                        $legajo = trim($data[2]) === '-' ? null : mb_convert_encoding(trim($data[2]), 'UTF-8');
                        $nombre = mb_convert_encoding(trim($data[3]), 'UTF-8');
                        $apellido = mb_convert_encoding(trim($data[4]), 'UTF-8');
                        $nombre_apellido = "$apellido $nombre";
                        $dependencia = mb_convert_encoding(trim($data[5]), 'UTF-8');

                        $fecha_original = trim($data[6]);
                        $fecha_mysql = null;

                        if (!empty($fecha_original)) {
                            $parts = explode('/', $fecha_original);
                            if (count($parts) === 3) {
                                $fecha_mysql = "{$parts[2]}-{$parts[1]}-{$parts[0]}";
                            }
                        }

                        $hora_registro = trim($data[7]);

                        if (empty($reloj) || empty($fecha_mysql) || empty($hora_registro)) {
                            throw new Exception("Faltan datos obligatorios");
                        }

                        if ($legajo === null) {
                            $legajo = 'TEMP-' . substr(md5($nombre_apellido), 0, 5);
                            $errors[] = "Línea $lineNumber: Legajo no proporcionado, usando temporal: $legajo";
                        }

                        $stmt->execute([
                            $reloj,
                            $legajo,
                            $nombre_apellido,
                            $dependencia,
                            $fecha_mysql,
                            $hora_registro
                        ]);

                        $insertedRows++;

                    } catch (Exception $e) {
                        $errors[] = "Línea $lineNumber: " . $e->getMessage();
                        Log::error("Error línea $lineNumber: " . $e->getMessage());
                    }
                }

                fclose($handle);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo abrir el archivo CSV',
                ]);
            }

            return response()->json([
                'success' => $insertedRows > 0,
                'message' => $insertedRows > 0 ? 'Archivo procesado con éxito' : 'No se insertaron registros',
                'inserted_rows' => $insertedRows,
                'total_lines_processed' => $lineNumber - 7,
                'error_count' => count($errors),
                'sample_errors' => array_slice($errors, 0, 5)
            ]);

        } catch (Exception $e) {
            Log::error('Error general en carga de relojes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo',
                'error' => $e->getMessage()
            ]);
        }
    }
}