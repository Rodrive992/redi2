@extends('layouts.app')

@section('title', 'REDI 2.0 - Real Prestación Control')

@section('content')
<div class="container-fluid px-0">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <!-- Tarjeta de encabezado y menú -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-primary mb-0">
                                <i class="bi bi-clock-history me-2"></i> Real Prestaciones Enviadas - Control
                            </h3>
                            @include('partials.herramientas-real-prestacion-menu')
                        </div>
                    </div>
                    <div class="card-body py-3">
                        @if(session('success'))
                            <div class="alert alert-success mb-2">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger mb-2">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Formulario de búsqueda -->
                        <form method="GET" action="{{ route('herramientas.real_prestacion_control') }}" class="row g-3 align-items-end mb-3">
                            <div class="col-md-4">
                                <label for="mes" class="form-label">Mes</label>
                                <select name="mes" id="mes" class="form-select form-select-sm">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $mes == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->locale('es')->isoFormat('MMMM') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="ano" class="form-label">Año</label>
                                <select name="ano" id="ano" class="form-select form-select-sm">
                                    @foreach(range(now()->year - 1, now()->year + 1) as $a)
                                        <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>{{ $a }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="bi bi-search me-1"></i> Buscar
                                </button>
                            </div>
                        </form>

                        <!-- Tabla -->
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Dependencia</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Archivo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($resultados as $resultado)
                                        @php $count = count($resultado['archivos']); @endphp
                                        @if($count > 0)
                                            @foreach($resultado['archivos'] as $i => $archivo)
                                                <tr>
                                                    @if($i == 0)
                                                        <td rowspan="{{ $count }}">{{ $resultado['nombre'] }}</td>
                                                    @endif
                                                    <td>{{ $archivo->usuario_envio ?? 'No disponible' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($archivo->fecha_subida)->format('d-m-Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ Storage::url($archivo->ruta_archivo) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-download me-1"></i> Descargar
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded-pill
                                                            @if(stripos($archivo->estado, 'Autorizado') !== false) bg-success
                                                            @elseif(stripos($archivo->estado, 'Pendiente') !== false) bg-warning text-dark
                                                         
                                                            @else bg-secondary
                                                            @endif">
                                                            {{ ucfirst($archivo->estado) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>{{ $resultado['nombre'] }}</td>
                                                <td colspan="4" class="text-muted">
                                                    <i class="bi bi-ban me-2"></i> No se ha cargado la real prestación.
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="bi bi-ban me-2"></i> No hay resultados disponibles.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- /.table-responsive -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</div>
@endsection