@extends('layouts.app')

@section('title', 'REDI 2.0 - Panel Externo')

@section('content')
<div class="container-fluid px-0">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-primary mb-0">
                                <i class="bi bi-tools me-2"></i> Panel de Herramientas - Personal - UNCA
                            </h3>
                            @include('partials.herramientas-menu-externo')
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="row g-4">
                            <!-- Real Prestación -->
                            <div class="col-md-4">
                                <div class="card h-100 border-warning hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Real Prestación</h5>
                                        <p class="card-text">Enviar y consultar Real Prestación</p>
                                        <a href="{{ route('herramientas.real_prestacion_externo') }}" class="btn btn-warning stretched-link">Acceder</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Asistencia -->
                            <div class="col-md-4">
                                <div class="card h-100 border-danger hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-people-fill text-danger" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Asistencia</h5>
                                        <p class="card-text">Control de asistencia</p>
                                        @if(Auth::user()->desempenio === 'unai')
                                            <a href="{{ route('herramientas.asistencia_externo_uai') }}" class="btn btn-danger stretched-link">Acceder</a>
                                        @else
                                            <a href="{{ route('herramientas.asistencia_externo') }}" class="btn btn-danger stretched-link">Acceder</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Planta -->
                            <div class="col-md-4">
                                <div class="card h-100 border-primary hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Planta del Personal</h5>
                                        <p class="card-text">Planta de la dependencia</p>
                                        <a href="{{ route('herramientas.consultar_bases_externo') }}" class="btn btn-primary stretched-link">Acceder</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection