<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchivosRealPrestacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class RealPrestacionHistorialController extends Controller
{
    public function index_externo()
    {
        $user = Auth::user();

        $query = ArchivosRealPrestacion::query();

        if ($user->dependencia === 'sgrl' && $user->permiso === 'autorizar') {
            // Usuario sgrl con permiso autorizar: traer todos los de sgrl sin filtrar desempeño
            $query->where('dependencia', 'sgrl');
        } else {
            // Comportamiento normal: filtrar por dependencia y desempeño/null
            $query->where('dependencia', $user->dependencia);

            if ($user->desempenio) {
                $query->where('desempenio', $user->desempenio);
            } else {
                $query->whereNull('desempenio');
            }
        }

        $archivos = $query->orderBy('fecha_subida', 'desc')->get();

        return view('herramientas.real_prestacion_historial_externo', compact('archivos'));
    }
    public function index_dgp()
    {
        $user = Auth::user();

        if ($user->dependencia=='dgp'){
            $dependenciadgp='secf';
        }
        
        // Obtener archivos según dependencia y desempeño del usuario
        $query = ArchivosRealPrestacion::where('dependencia', $dependenciadgp);
        
       
        
        // Ordenar por fecha_subida en lugar de created_at
        $archivos = $query->orderBy('fecha_subida', 'desc')->get();
        
        return view('herramientas.real_prestacion_historial', compact('archivos'));
    }

    public function borrar($id)
    {
        try {
            $archivo = ArchivosRealPrestacion::findOrFail($id);
            
            // Validar permisos y estado
            if (Auth::user()->permiso != 'editar' || $archivo->estado != 'pendiente') {
                return back()->with('error', 'No tienes permiso para realizar esta acción');
            }
            
            // Validar fecha (solo entre 1ro y 10mo del mes)
            $diaActual = now()->day;
            if ($diaActual < 1 || $diaActual > 10) {
                return back()->with('error', 'Solo se pueden borrar archivos entre el 1ro y 10mo día del mes');
            }
            
            // Eliminar archivo físico
            Storage::delete($archivo->ruta_archivo);
            
            // Eliminar registro
            $archivo->delete();
            
            return back()->with('success', 'El archivo se ha borrado correctamente');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al borrar el archivo: ' . $e->getMessage());
        }
    }

    public function autorizar($id)
    {
        try {
            $archivo = ArchivosRealPrestacion::findOrFail($id);
            
            // Validar permisos
            if (Auth::user()->permiso != 'autorizar') {
                return back()->with('error', 'No tienes permiso para autorizar archivos');
            }
            
            // Actualizar estado sin usar timestamps
            $archivo->update([
                'estado' => 'Autorizado',
                'usuario_auto' => Auth::user()->cuil
            ]);
            
            return back()->with('success', 'El archivo ha sido autorizado correctamente');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al autorizar el archivo: ' . $e->getMessage());
        }
    }
}   