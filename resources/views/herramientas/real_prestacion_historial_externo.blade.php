@extends('layouts.app')

@section('title', 'REDI 2.0 - Historial Real Prestación')

@section('content')
<div class="container-fluid px-0">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-primary mb-0">
                                <i class="bi bi-clock-history me-2"></i> Historial Real Prestación - UNCA
                            </h3>
                            @include('partials.herramientas-real-prestacion-menu')
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mensajes de sesión -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Alertas informativas -->
                        <div class="alert alert-primary mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i> La Real Prestación podrá eliminarse y volver a cargar únicamente entre el 1ro y 10mo día del mes
                        </div>
                        <div class="alert alert-danger mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Una vez autorizado el envío no podrá modificarse.
                        </div>

                        <!-- Tabla de historial -->
                        <div class="table-responsive">
                            <h4 class="mb-3">Real Prestación - Historial</h4>
                            <table class="table table-bordered table-hover" id="historialTable">
                                <thead class="thead-dark">
                                    <tr>    
                                        <th>ID</th>
                                        <th>Usuario</th>    
                                        <th>Fecha de Envío</th>
                                        <th>Mes/Año</th>
                                        <th>Archivo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($archivos as $archivo)
                                        <tr>
                                            <td>{{ $archivo->id }}</td>
                                            <td>{{ $archivo->usuario_envio ?? 'Usuario no disponible' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($archivo->fecha_subida)->format('d-m-Y') }}</td>
                                            <td>{{ $archivo->mes }}/{{ $archivo->ano }}</td>
                                            <td>
                                                <a href="{{ Storage::url($archivo->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i> Descargar
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($archivo->estado == 'pendiente') bg-warning text-dark
                                                    @elseif($archivo->estado == 'Autorizado') bg-success                                                    
                                                    @endif">
                                                    {{ ucfirst($archivo->estado) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(auth()->user()->permiso == 'editar' && $archivo->estado == 'pendiente')
                                                    <form method="POST" action="{{ route('real_prestacion.borrar', $archivo->id) }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas borrar este archivo?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash me-1"></i> Borrar
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(auth()->user()->permiso == 'autorizar' && $archivo->estado == 'pendiente')
                                                    <form method="POST" action="{{ route('real_prestacion.autorizar', $archivo->id) }}" class="d-inline ms-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-circle me-1"></i> Autorizar
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!in_array(auth()->user()->permiso, ['editar', 'autorizar']) || $archivo->estado != 'pendiente')
                                                    <span class="text-muted">No disponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="d-flex align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-ban me-2"></i>
                                                    No se han encontrado archivos de Real Prestación
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $('#historialTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            order: [[2, 'desc']] // Ordenar por fecha de envío descendente
        });
    });
</script>
@endpush