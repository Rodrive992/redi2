@extends('layouts.app')

@section('title', 'Sistema Digital de Asistencia UNCA')

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
                            <i class="bi bi-calendar-check"></i> Sistema Digital de Asistencia UNCA - Informes
                        </h4>
                        @include('partials.herramientas-menu-externo')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filtros -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('asistencia_externo.consultar') }}" method="POST" class="row g-2 mb-4">
                        @csrf                        
                        <div class="col-md-2">
                            <input type="date" name="desde" id="desde" class="form-control form-control-sm" value="{{ $desde ?? '' }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="hasta" id="hasta" class="form-control form-control-sm" value="{{ $hasta ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="nombre_legajo" class="form-control form-control-sm" placeholder="Nombre o Legajo" value="{{ $nombre_legajo ?? '' }}">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-file-earmark-text"></i> Generar
                            </button>
                        </div>
                    </form>

                    @if(isset($asistencia) && count($asistencia))
                    <form action="{{ route('asistencia_externo.exportar') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="desde" value="{{ $desde }}">
                        <input type="hidden" name="hasta" value="{{ $hasta }}">                        
                        <input type="hidden" name="nombre_legajo" value="{{ $nombre_legajo }}">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                        </button>
                    </form>
                    @endif

                    <!-- Tabla de asistencia -->
                    @if(isset($asistencia))
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Legajo</th>
                                    <th>Nombre</th>
                                    @foreach($fechas as $dia)
                                        <th>{{ \Carbon\Carbon::parse($dia)->format('d/m') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistencia as $legajo => $dias)
                                <tr>
                                    <td>{{ $legajo }}</td>
                                    <td>{{ $nombres[$legajo] ?? '' }}</td>
                                    @foreach($fechas as $dia)
                                        <td>
                                            @if(isset($dias[$dia]) && count($dias[$dia]))
                                                {{ implode(' - ', $dias[$dia]) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @elseif(isset($fechas))
                        <div class="alert alert-warning">No se encontraron registros para los filtros seleccionados.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection