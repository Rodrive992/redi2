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
                    <form class="row g-3">
                        <div class="col-md-6">
                            <label for="procedimiento" class="form-label">Seleccione un procedimiento</label>
                            <select id="procedimiento" class="form-select" onchange="cargarProcedimiento(this)">
                                <option selected disabled>-- Elegir procedimiento --</option>
                                @foreach($procedimientos as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div class="mt-4" id="contenedor-procedimiento">
                        <!-- Aquí se carga el contenido dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cargarProcedimiento(select) {
    const value = select.value;
    if (!value) return;

    fetch(`/procedimientos/${value}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('contenedor-procedimiento').innerHTML = html;
        });
}
</script>
@endsection