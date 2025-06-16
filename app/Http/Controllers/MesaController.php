<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

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
}