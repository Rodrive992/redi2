@extends('layouts.app')

@section('title', 'Procedimientos')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary"><i class="bi bi-check2-circle me-2"></i> Procedimientos</h4>
                    @include('partials.herramientas-menu')
                </div>
                <div class="card-body">
                    <form class="row g-3" method="GET" action="{{ route('herramientas.procedimientos') }}">
                        <div class="col-md-6">
                       
                            <select id="procedimiento" name="procedimiento" class="form-select" required>
                                <option disabled selected value="">-- Elegir procedimiento --</option>

                                <optgroup label="Seguros">
                                    <option value="alta_vida" {{ request('procedimiento') == 'alta_vida' ? 'selected' : '' }}>Alta seguro de vida</option>
                                    <option value="baja_vida" {{ request('procedimiento') == 'baja_vida' ? 'selected' : '' }}>Baja seguro de vida</option>
                                    <option value="alta_sepelio" {{ request('procedimiento') == 'alta_sepelio' ? 'selected' : '' }}>Alta seguro de sepelio</option>
                                    <option value="baja_sepelio" {{ request('procedimiento') == 'baja_sepelio' ? 'selected' : '' }}>Baja seguro de sepelio</option>
                                </optgroup>

                                <optgroup label="Legajos">
                                    <option value="toma_posesion" {{ request('procedimiento') == 'toma_posesion' ? 'selected' : '' }}>Toma de posesión</option>
                                    <option value="adicional_titulo_posgrado" {{ request('procedimiento') == 'adicional_titulo_posgrado' ? 'selected' : '' }}>Adicional por título de posgrado</option>
                                    <option value="designaciones_automaticas" {{ request('procedimiento') == 'desiganciones_automaticas' ? 'selected' : '' }}>Designaciones automáticas</option>
                                    <option value="casos_incompatibilidad" {{ request('procedimiento') == 'casos_incompatibilidad' ? 'selected' : '' }}>Casos de Incompatibilidad</option>
                                </optgroup>

                                <optgroup label="Licencias">
                                    <option value="licencia_maternidad" {{ request('procedimiento') == 'licencia_maternidad' ? 'selected' : '' }}>Licencia por maternidad</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Ver</button>
                        </div>
                    </form>

                    @if($procedimientoSeleccionado)
                        <hr class="my-4">
                        <div id="contenedor-procedimiento">
                            @include("herramientas.procedimientos.$procedimientoSeleccionado")
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection