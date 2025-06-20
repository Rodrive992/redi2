@extends('layouts.app')

@section('title', 'Cruce de Datos - Compatibilidad')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Tarjeta de Título -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-check2-circle"></i> Cruce de Datos - Compatibilidad
                        </h4>
                        @include('partials.herramientas-menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('herramientas.compatibilidad') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        <input type="text" class="form-control" name="dni" 
                               placeholder="Ingresar DNI..." value="{{ $dni ?? '' }}" 
                               required pattern="[0-9]+" title="Solo números">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    @if(isset($empleados) && $empleados->isNotEmpty())
    <div class="card shadow-lg mb-5">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $empleados->first()->nombre }}</h4>
                <span class="badge bg-light text-dark fs-6">DNI: {{ $empleados->first()->dni }}</span>
            </div>
        </div>
        <div class="card-body">
            <!-- Botón Exportar -->
            <div class="text-end mb-3">
                <form action="{{ route('herramientas.exportar_compatibilidad') }}" method="GET">
                    <input type="hidden" name="dni" value="{{ $dni }}">
                    <button type="submit" class="btn btn-success">
                        <i></i> Exportar
                    </button>
                </form>
            </div>

            <!-- Tabla de resultados -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Dependencia</th>
                            <th>Cargo</th>
                            <th>Alta</th>
                            <th>Carácter</th>
                            <th>Dedicación</th>
                            <th>Hs</th>
                            <th>Licencia</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total_horas = 0; @endphp
                        @foreach ($empleados as $empleado)
                        @php
                            if (empty($empleado->licencia) || strtolower(trim($empleado->licencia)) === 'null') {
                                $total_horas += $empleado->hs;
                            }
                        @endphp
                        <tr>
                            <td>{{ $empleado->dependencia }}</td>
                            <td>{{ $empleado->cargo }}</td>
                            <td>{{ $empleado->alta_cargo }}</td>
                            <td>{{ $empleado->caracter }}</td>
                            <td>{{ $empleado->dedicacion }}</td>
                            <td>{{ $empleado->hs }}</td>
                            <td>{{ $empleado->licencia ?? '-' }}</td>
                            <td>{{ $empleado->alta_licencia ?? '-' }}</td>
                            <td>{{ $empleado->baja_licencia ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">Total horas (sin licencia)</th>
                            <th class="fw-bold">{{ $total_horas }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @elseif(request()->has('dni'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> No se encontraron resultados para el DNI: {{ $dni }}
    </div>
    @endif
</div>
@endsection