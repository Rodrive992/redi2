<?php

namespace App\Http\Controllers;

use App\Models\PlantaUnca;
use Illuminate\Support\Facades\Auth;

class ConsultarBasesExternoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $dependencia = $user->dependencia ?? null;
        $desempenio = $user->desempenio ?? null;

        $query = (new PlantaUnca())->newQuery();

        $query->where('dependencia', $dependencia)
              ->where('caracter', '!=', 'DEXT');

        if ($dependencia === 'efme' && $desempenio) {
            $query->where('desempenio', $desempenio);
        }

        $planta = $query->orderBy('nombre')->paginate(20);

        return view('herramientas.consultar_bases_externo', compact('planta'));
    }
}