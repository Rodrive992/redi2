<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MesaController extends Controller
{
    public function recibir($id)
    {
        try {
            $documento = Mesa::findOrFail($id);
            
            $documento->update([
                'estado' => 'Recibido'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Documento recibido correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al recibir el documento: ' . $e->getMessage()
            ], 500);
        }
    }

        public function reenviar($id, Request $request)
        {
            $request->validate([
                'destino' => 'required|string|max:255'
            ]);

            try {
                $documento = Mesa::findOrFail($id);
                
                $documento->update([
                    'entregado_a' => $request->destino,
                    'estado' => 'Pendiente'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Documento reenviado correctamente'
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al reenviar el documento'
                ], 500);
            }
        }

        public function mesaEntrada(Request $request)
{
    $search = $request->input('search');
    
    $query = Mesa::query();
    
    if ($search) {
        // Verificamos si la búsqueda es una fecha en formato DD/MM/YYYY
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search)) {
            // Si es una fecha, la convertimos a formato YYYY-MM-DD
            list($day, $month, $year) = explode('/', $search);
            $search = "$year-$month-$day";  // Convertir a formato adecuado para la base de datos
        }
        
        // Realizamos la búsqueda en los campos con insensibilidad a mayúsculas/minúsculas
        $query->where(function($q) use ($search) {
            // Convertimos todo a minúsculas para la comparación sin distinción de mayúsculas/minúsculas
            $q->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(id) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(entrada) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(dependencia) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(entregado_a) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(usuario) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(estado) LIKE ?', ['%' . strtolower($search) . '%'])
              // Buscar fecha, asegurándonos que el formato esté correctamente adaptado
              ->orWhereRaw('DATE(fecha) LIKE ?', ['%' . $search . '%']);
              
        });
    }

    $mesas = $query->orderBy('fecha', 'desc')->paginate(25);
    
    return view('herramientas.mesa_entrada', compact('mesas'));
}

    public function registrar(Request $request)
{
    $request->validate([
        'entrada' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'dependencia' => 'required|string|max:255',
        'entregado_a' => 'required|string|max:255'
    ]);

    Mesa::create([
        'usuario' => Auth::user()->name,
        'entrada' => $request->entrada,
        'nombre' => $request->nombre,
        'dependencia' => $request->dependencia,
        'entregado_a' => $request->entregado_a,
        'estado' => 'Pendiente',
        'fecha' => now()
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Registro creado exitosamente'
    ]);
}

    public function editar($id)
    {
        $mesa = Mesa::findOrFail($id);
        return response()->json($mesa);
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'entrada' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'dependencia' => 'required|string|max:255',
            'entregado_a' => 'required|string|max:255'
        ]);

        $mesa = Mesa::findOrFail($id);
        $mesa->update($request->only(['entrada', 'nombre', 'dependencia', 'entregado_a']));

        return response()->json([
            'success' => true,
            'message' => 'Registro actualizado correctamente'
        ]);
    }

    public function eliminar($id)
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el registro: ' . $e->getMessage()
            ], 500);
        }
    }
}