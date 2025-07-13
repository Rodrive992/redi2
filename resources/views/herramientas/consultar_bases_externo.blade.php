@extends('layouts.app')

@section('title', 'Control de Planta')

@section('content')
@php
    $userDependencia = auth()->user()->dependencia ?? '';
    $userDesempenio = auth()->user()->desempenio ?? '';
@endphp

<div class="container-fluid px-4 py-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-person-badge"></i> Planta de Personal UNCA
                        </h4>
                        @include('partials.herramientas-menu-externo')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(isset($planta) && $planta->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Legajo</th>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Ingreso</th>
                                        <th>Cargo</th>
                                        <th>Escalafón</th>
                                        <th>Carácter</th>
                                        <th>Dedicación</th>
                                        <th>Agrupamiento</th>
                                        <th>Subrogancia</th>
                                        <th>Alta Cargo</th>
                                        <th>Licencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($planta as $item)
                                        <tr>
                                            <td>{{ $item->legajo }}</td>
                                            <td>{{ $item->nombre }}</td>
                                            <td>{{ $item->dni }}</td>
                                            <td>{{ $item->fecha_ingreso }}</td>
                                            <td>{{ $item->cargo }}</td>
                                            <td>{{ $item->escalafon }}</td>
                                            <td>{{ $item->caracter }}</td>
                                            <td>{{ $item->dedicacion }}</td>
                                            <td>{{ $item->agrupamiento }}</td>
                                            <td>{{ $item->subrogancia }}</td>
                                            <td>{{ $item->alta_cargo }}</td>
                                            <td>{{ $item->licencia }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Mostrando {{ $planta->firstItem() }} a {{ $planta->lastItem() }} de {{ $planta->total() }} registros
                            </div>
                            <div>
                                {{ $planta->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No se encontraron registros para su dependencia y desempeño.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection