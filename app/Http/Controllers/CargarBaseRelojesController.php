<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class CargarBaseRelojesController extends Controller
{
    public function cargar(Request $request)
    {
        // Configuración inicial
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        Log::debug('==== INICIO DE CARGA DE RELOJES ====');

        try {
            // Validación del request
            $validator = Validator::make($request->all(), [
                'base' => 'required|in:relojes',
                'csvFile' => 'required|file|mimes:csv,txt|max:102400' // 100MB
            ]);

            if ($validator->fails()) {
                Log::error('Error de validación', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('csvFile');

            // Verificación del archivo
            if (!$file->isValid()) {
                throw new Exception('El archivo no se cargó correctamente: ' . $file->getErrorMessage());
            }

            $filePath = $file->getRealPath();
            Log::debug("Archivo recibido: " . $file->getClientOriginalName());

            // Procesamiento del archivo
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                throw new Exception('No se pudo abrir el archivo');
            }

            DB::beginTransaction();
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("
                INSERT INTO asistencia_reloj 
                (reloj, legajo, nombre_apellido, dependencia, fecha, registro, metodo_verificacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $insertedRows = 0;
            $errors = [];
            $lineNumber = 0;

            // Saltar cabeceras (6 líneas)
            for ($i = 0; $i < 6; $i++) {
                fgetcsv($handle, 0, ';');
                $lineNumber++;
            }

            // Procesar cada línea
            while (($data = fgetcsv($handle, 0, ';')) {
                $lineNumber++;
                $originalLine = implode(';', $data);

                try {
                    if (count($data) < 8) {
                        throw new Exception('Línea incompleta');
                    }

                    // Procesar campos
                    $reloj = $this->cleanField($data[0]);
                    $legajo = $this->cleanField($data[2]);
                    $nombre = $this->cleanField($data[3]);
                    $apellido = $this->cleanField($data[4]);
                    $dependencia = $this->cleanField($data[5]);
                    $fecha = $this->parseDate($data[6]);
                    $hora = $this->cleanField($data[7]);
                    $metodo = $this->cleanField($data[8] ?? null);

                    // Validar campos obligatorios
                    if (empty($reloj) || empty($fecha) || empty($hora)) {
                        throw new Exception('Campos obligatorios vacíos');
                    }

                    // Manejar legajos vacíos
                    if (empty($legajo) || $legajo === '-') {
                        $legajo = 'TEMP-' . substr(md5($nombre . $apellido), 0, 8);
                        $errors[] = "Línea $lineNumber: Legajo temporal asignado";
                    }

                    $nombreCompleto = trim("$apellido $nombre");

                    // Insertar en la base de datos
                    $stmt->execute([
                        $reloj,
                        $legajo,
                        $nombreCompleto,
                        $dependencia,
                        $fecha,
                        $hora,
                        $metodo
                    ]);

                    $insertedRows++;

                } catch (Exception $e) {
                    $errors[] = "Línea $lineNumber: " . $e->getMessage();
                    Log::error("Error en línea $lineNumber: " . $e->getMessage());
                }
            }

            fclose($handle);
            DB::commit();

            // Preparar respuesta
            $response = [
                'success' => true,
                'message' => "Archivo procesado correctamente. $insertedRows registros insertados.",
                'stats' => [
                    'total_lineas' => $lineNumber,
                    'insertados' => $insertedRows,
                    'errores' => count($errors),
                    'tasa_exito' => $lineNumber > 0 ? round(($insertedRows/$lineNumber)*100, 2) . '%' : '0%'
                ],
                'primeros_errores' => array_slice($errors, 0, 5)
            ];

            return response()->json($response);

        } catch (Exception $e) {
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            DB::rollBack();

            Log::error('Error en el procesamiento: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    private function cleanField($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim($value);
        $value = mb_convert_encoding($value, 'UTF-8');
        return $value === '-' ? null : $value;
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        $parts = explode('/', $dateString);
        if (count($parts) === 3) {
            return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
        }

        throw new Exception("Formato de fecha inválido: '$dateString'");
    }
}