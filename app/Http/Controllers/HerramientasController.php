<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function realPrestacion()
    {
        return view('herramientas.real_prestacion', [
            'title' => 'Real Prestación'
        ]);
    }

    /**
     * Muestra la herramienta Plantas
     */
    public function plantas()
    {
        return view('herramientas.plantas', [
            'title' => 'Plantas'
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
