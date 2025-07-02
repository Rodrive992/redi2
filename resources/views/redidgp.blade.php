@extends('layouts.app')

@section('title', 'REDI 2.0 - Panel DGP')

@section('content')
<div class="container-fluid px-0">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-primary mb-0">
                                <i class="bi bi-tools me-2"></i>Panel de Herramientas - Dirección General de Personal
                            </h3>
                            @include('partials.herramientas-menu')
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="row g-4">
                            <!-- Tarjeta Mesa de Entrada -->
                            <div class="col-md-4">
                                <div class="card h-100 border-primary hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-inbox-fill text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Mesa de Entrada</h5>
                                        <p class="card-text">Gestión de documentos entrantes</p>
                                        <a href="{{ route('herramientas.mesa_entrada') }}" class="btn btn-primary stretched-link">Acceder</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Tarjeta Compatibilidad -->
                            <div class="col-md-4">
                                <div class="card h-100 border-success hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-check2-circle text-success" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Compatibilidad</h5>
                                        <p class="card-text">Análisis de compatibilidades</p>
                                        <a href="{{ route('herramientas.compatibilidad') }}" class="btn btn-success stretched-link">Acceder</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Tarjeta Certificados -->
                            <div class="col-md-4">
                                <div class="card h-100 border-info hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-file-earmark-text text-info" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Certificados</h5>
                                        <p class="card-text">Gestión de certificados</p>
                                        <a href="{{ route('herramientas.certificados') }}" class="btn btn-info stretched-link">Acceder</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Repetir el mismo patrón para las demás herramientas -->
                            <!-- Procedimientos -->
                            <div class="col-md-4">
                                <div class="card h-100 border-warning hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Real Prestación</h5>
                                        <p class="card-text">Enviados de real prestación de servicios</p>
                                        <a href="{{ route('herramientas.real_prestacion') }}" class="btn btn-warning stretched-link">Acceder</a>
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
                                        <a href="{{ route('herramientas.asistencia') }}" class="btn btn-danger stretched-link">Acceder</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Vecimientos -->
                            <div class="col-md-4">
                                <div class="card h-100 border-primary hover-shadow">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-hourglass-split text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="card-title">Vencimientos</h5>
                                        <p class="card-text">Cargos con vencimiento - Bajas</p>
                                        <a href="{{ route('herramientas.vencimientos') }}" class="btn btn-primary stretched-link">Acceder</a>
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