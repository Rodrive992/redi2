<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MesaController extends Controller
{
    public function recibir($id)
    {
        $mesa = Mesa::findOrFail($id);
        
        $mesa->update([
            'estado' => 'Recibido',
            'recibido_por' => Auth::user()->name,
            'fecha_recibido' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function reenviar($id, Request $request)
    {
        $request->validate([
            'destino' => 'required|string|max:255',
            'observaciones' => 'nullable|string'
        ]);

        $mesa = Mesa::findOrFail($id);
        
        $mesa->update([
            'entregado_a' => $request->destino,
            'observaciones' => $request->observaciones,
            'estado' => 'Reenviado',
            'reenviado_por' => Auth::user()->name,
            'fecha_reenviado' => now()
        ]);

        return response()->json(['success' => true]);
    }
}