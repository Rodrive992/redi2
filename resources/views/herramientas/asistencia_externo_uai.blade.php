@extends('layouts.app')

@section('title', 'Sistema Digital de Asistencia UNCA')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-calendar-check"></i> Sistema Digital de Asistencia UNCA - Informes - Unidad de Auditoria Interna
                        </h4>
                        @include('partials.herramientas-menu-externo')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Alertas informativas -->
    <div class="alert alert-primary mb-4">
    <i class="bi bi-info-circle-fill me-2"></i> Elija el periodo y la depedencia de control de los registros de los relojes.
    </div>

    <!-- Filtros -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('asistencia_externo_uai.consultar') }}" method="POST" class="row g-2 mb-4">
                        @csrf
                        <div class="col-md-2">
                            <input type="date" name="desde" id="desde" class="form-control form-control-sm" value="{{ $desde ?? '' }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="hasta" id="hasta" class="form-control form-control-sm" value="{{ $hasta ?? '' }}" required>
                        </div>
                        <div class="col-md-2">
                            <select name="dependencia" id="dependencia" class="form-select form-select-sm" required>
                                <option value="enet" {{ ($dependencia ?? '') == 'enet' ? 'selected' : '' }}>ENET</option>
                                <option value="sbya" {{ ($dependencia ?? '') == 'sbya' ? 'selected' : '' }}>SBYA</option>
                                <option value="sgrl" {{ ($dependencia ?? '') == 'sgrl' ? 'selected' : '' }}>SGRL</option>
                                <option value="dgp" {{ ($dependencia ?? '') == 'dgp' ? 'selected' : '' }}>DGP</option>
                                <option value="efme" {{ ($dependencia ?? '') == 'efme' ? 'selected' : '' }}>EFME</option>
                                <option value="srii" {{ ($dependencia ?? '') == 'srii' ? 'selected' : '' }}>SRII</option>
                                <option value="sext" {{ ($dependencia ?? '') == 'sext' ? 'selected' : '' }}>SEXT</option>
                                <option value="siyp" {{ ($dependencia ?? '') == 'siyp' ? 'selected' : '' }}>SIYP</option>
                                <option value="saca" {{ ($dependencia ?? '') == 'saca' ? 'selected' : '' }}>SACA</option>
                                <option value="earq" {{ ($dependencia ?? '') == 'earq' ? 'selected' : '' }}>EARQ</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="desempenio" id="desempenio" class="form-select form-select-sm">
                                <option value="">Sin desempeño</option>
                                <option value="prim" {{ ($desempenio ?? '') == 'prim' ? 'selected' : '' }}>Primaria</option>
                                <option value="secu" {{ ($desempenio ?? '') == 'secu' ? 'selected' : '' }}>Secundaria</option>
                                <option value="inic" {{ ($desempenio ?? '') == 'inic' ? 'selected' : '' }}>Inicial</option>
                                <option value="dgom" {{ ($desempenio ?? '') == 'dgom' ? 'selected' : '' }}>DGOM</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="nombre_legajo" class="form-control form-control-sm" placeholder="Nombre o Legajo" value="{{ $nombre_legajo ?? '' }}">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-file-earmark-text"></i> Generar
                            </button>
                        </div>
                    </form>

                    @if(isset($asistencia) && count($asistencia))
                    <form action="{{ route('asistencia_externo_uai.exportar') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="desde" value="{{ $desde }}">
                        <input type="hidden" name="hasta" value="{{ $hasta }}">
                        <input type="hidden" name="dependencia" value="{{ $dependencia }}">
                        <input type="hidden" name="desempenio" value="{{ $desempenio }}">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dependenciaSelect = document.getElementById('dependencia');
    const desempenioSelect = document.getElementById('desempenio');

    function controlarDesempenio() {
        const dep = dependenciaSelect.value;
        const desempenioActual = desempenioSelect.value;

        if (dep === 'efme') {
            desempenioSelect.required = true;
            desempenioSelect.disabled = false;
            if (desempenioActual === 'dgom') {
                desempenioSelect.value = '';
            }
            mostrarOpciones(['', 'prim', 'secu', 'inic']);
        } else if (dep === 'sgrl') {
            desempenioSelect.required = false;
            desempenioSelect.disabled = false;
            if (['prim', 'secu', 'inic'].includes(desempenioActual)) {
                desempenioSelect.value = '';
            }
            mostrarOpciones(['', 'dgom']);
        } else {
            desempenioSelect.required = false;
            desempenioSelect.disabled = true;
            desempenioSelect.value = '';
        }
    }

    function mostrarOpciones(opcionesPermitidas) {
        const opciones = desempenioSelect.querySelectorAll('option');
        opciones.forEach(op => {
            op.hidden = !opcionesPermitidas.includes(op.value);
        });
    }

    dependenciaSelect.addEventListener('change', controlarDesempenio);
    controlarDesempenio();
});
</script>
@endsection