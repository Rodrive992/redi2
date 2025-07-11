<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchivosRealPrestacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HerramientasController extends Controller
{
    /**
     * Muestra la herramienta Mesa de Entrada
     */
    public function mesaEntrada()
    {
        return view('herramientas.mesa_entrada', [
            'title' => 'Mesa de Entrada'
        ]);
    }

    /**
     * Muestra la herramienta Compatibilidad
     */
    public function compatibilidad()
    {
        return view('herramientas.compatibilidad', [
            'title' => 'Compatibilidad'
        ]);
    }

    /**
     * Muestra la herramienta Certificados
     */
    public function certificados()
    {
        return view('herramientas.certificados', [
            'title' => 'Certificados'
        ]);
    }

    /**
     * Muestra la herramienta Procedimientos
     */
    public function procedimientos()
    {
        return view('herramientas.procedimientos', [
            'title' => 'Procedimientos'
        ]);
    }

    /**
     * Muestra la herramienta Asistencia
     */
    public function asistencia()
    {
        return view('herramientas.asistencia', [
            'title' => 'Asistencia'
        ]);
    }

    /**
     * Muestra la herramienta Vencimientos
     */
    public function vencimientos()
    {
        return view('herramientas.vencimientos', [
            'title' => 'Vencimientos'
        ]);
    }

    /**
     * Muestra la herramienta Real Prestación
     */
        public function realPrestacionHistorial()
    {
        $user = Auth::user();

        if ($user->dependencia == 'dgp') {
            $dependencia = 'secf';
        } else {
            $dependencia = $user->dependencia;
        }

        $query = ArchivosRealPrestacion::where('dependencia', $dependencia);

        if ($user->desempenio) {
            $query->where('desempenio', $user->desempenio);
        } else {
            $query->whereNull('desempenio');
        }

        $archivos = $query->orderBy('fecha_subida', 'desc')->get();

        return view('herramientas.real_prestacion_historial', compact('archivos'));
    }

    /**
     * Muestra la herramienta Plantas
     */
    public function consultarBases()
    {
        return view('herramientas.consultar_bases', [
            'title' => 'Consultar Bases'
        ]);
    }

    /**
     * Muestra la herramienta Carga de Bases
     */
    public function cargaBases()
    {
        return view('herramientas.carga_bases', [
            'title' => 'Carga de Bases'
        ]);
    }

    /**
     * Muestra la herramienta Suma Horarios
     */
    public function sumaHorarios()
    {
        return view('herramientas.suma_horarios', [
            'title' => 'Suma Horarios'
        ]);
    }

}
