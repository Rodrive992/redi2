@extends('layouts.app')

@section('title', 'Consultar Bases')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Tarjeta de Título y Herramientas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-check2-circle"></i> Consultar Bases de Datos
                        </h4>
                        @include('partials.herramientas-menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Contenido Principal -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Barra de herramientas -->
                    <div class="d-flex justify-content-between align-items-center mb-4 bg-light p-2 rounded">
                        <form action="{{ route('herramientas.consultar_bases') }}" method="GET" class="d-flex align-items-center gap-2 w-100 me-3">
                            <!-- Selector de Base -->
                            <div class="flex-grow-1" style="max-width: 220px;">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="bi bi-database"></i>
                                    </span>
                                    <select name="base" id="base" class="form-select form-select-sm">
                                        <option value="" disabled selected>Elegir base...</option>
                                        <option value="educacion" {{ ($base ?? '') == 'educacion' ? 'selected' : '' }}>Educación Provincia</option>
                                        <option value="administracion" {{ ($base ?? '') == 'administracion' ? 'selected' : '' }}>Administración Provincia</option>
                                        <option value="unca" {{ ($base ?? '') == 'unca' ? 'selected' : '' }}>Planta Unca</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Campo de Búsqueda -->
                            <div class="flex-grow-1">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar registros..." value="{{ $search ?? '' }}">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Botón de Exportar -->
                        @if(isset($base) && $base)
                        <form action="{{ route('herramientas.exportar_bases') }}" method="GET" class="ms-auto">
                            <input type="hidden" name="base" value="{{ $base }}">
                            <input type="hidden" name="search" value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-success btn-sm" title="Exportar a Excel">
                                <i></i> Exportar
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- Tabla de registros -->
                    @if(isset($results) && $results->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Legajo</th>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Ingreso</th>
                                    <th>Dependencia</th>
                                    <th>Desempeño</th>
                                    <th>Cargo</th>
                                    <th>Escalafón</th>
                                    <th>Agrupamiento</th>
                                    <th>Subrogancia</th>
                                    <th>Caracter</th>
                                    <th>Dedicación</th>
                                    <th>Alta</th>
                                    <th>Vencimiento</th>
                                    <th>Puntaje</th>
                                    <th>Horas</th>
                                    <th>Licencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $item)
                                <tr>
                                    <td>{{ $item->legajo }}</td>
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->dni }}</td>
                                    <td>{{ $item->fecha_ingreso }}</td>
                                    <td>{{ $item->dependencia }}</td>
                                    <td>{{ $item->desempenio }}</td>
                                    <td>{{ $item->cargo }}</td>
                                    <td>{{ $item->escalafon }}</td>
                                    <td>{{ $item->agrupamiento }}</td>
                                    <td>{{ $item->subrogancia }}</td>
                                    <td>{{ $item->caracter }}</td>
                                    <td>{{ $item->dedicacion }}</td>
                                    <td>{{ $item->alta_cargo }}</td>
                                    <td>{{ $item->vencimiento_cargo }}</td>
                                    <td>{{ $item->puntaje }}</td>
                                    <td>{{ $item->hs }}</td>
                                    <td>{{ $item->licencia }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Mostrando {{ $results->firstItem() }} a {{ $results->lastItem() }} de {{ $results->total() }} registros
                        </div>
                        <div>
                            {{ $results->appends(['search' => $search ?? '', 'base' => $base ?? ''])->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        Seleccione una base de datos para comenzar
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection