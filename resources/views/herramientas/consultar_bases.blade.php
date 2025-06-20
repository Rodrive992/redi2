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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="col-md-4">
                            <form action="{{ route('herramientas.consultar_bases') }}" method="GET">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control form-control-sm" 
                                           placeholder="Buscar registros..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabla de registros -->
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">Usuario</th>
                                    <th width="10%">Entrada</th>
                                    <th width="15%">Nombre</th>
                                    <th width="15%">Dependencia</th>
                                    <th width="12%">Entregado a</th>
                                    <th width="8%">Estado</th>
                                    <th width="10%">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Mostrando registros
                        </div>
                        <div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection