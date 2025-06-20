@extends('layouts.app')

@section('title', 'Mesa de Entrada')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Tarjeta de Título y Herramientas -->
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
                       
                        
                        
                        
                        


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection