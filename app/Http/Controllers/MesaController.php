<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Añade esta línea

class MesaController extends Controller
{
    public function recibir($id)
    {
        DB::table('mesa')
            ->where('id', $id)
            ->update([
                'estado' => 'Recibido',
                'recibido_por' => Auth::user()->name, // Registra quién lo recibió
                'fecha_recibido' => now()
            ]);

        return response()->json(['success' => true]);
    }
}
