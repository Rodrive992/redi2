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
                            <i class="bi bi-tools"></i> Sistema Digital de Asistencia UNCA - Panel de control
                        </h4>
                        @include('partials.herramientas-asistencia-menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <form action="{{ route('herramientas.panel_control') }}" method="GET" class="row g-2 mb-4">
                        <div class="col-md-2">
                            <select name="dependencia" id="dependencia" class="form-select form-select-sm" required>
                                @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                    <option value="{{ $dep }}" {{ ($dependencia ?? '') == $dep ? 'selected' : '' }}>{{ strtoupper($dep) }}</option>
                                @endforeach
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
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Filtrar
                            </button>
                        </div>
                    </form>

                    <!-- Botones de carga -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-success btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#formularioControl">
                            <i class="bi bi-person-plus"></i> Asignar Control de Asistencia
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#formularioLegajo">
                            <i class="bi bi-file-earmark-plus"></i> Asignar Legajo
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#formularioReloj">
                            <i class="bi bi-plus-circle"></i> Asignar Reloj
                        </button>
                    </div>

                    <!-- Formulario Control -->
                    <div class="collapse mb-4" id="formularioControl">
                        <div class="card card-body border border-success">
                            <form action="{{ route('herramientas_asistencia.guardar_control') }}" method="POST" class="row g-2">
                                @csrf
                                <div class="col-md-3">
                                    <input type="text" name="nombre_usuario" class="form-control form-control-sm" placeholder="Nombre completo" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="cuil_usuario" class="form-control form-control-sm" placeholder="CUIL" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="dependencia_usuario" id="dep_control" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Dependencia</option>
                                        @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                            <option value="{{ $dep }}">{{ strtoupper($dep) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="desempenio_usuario" id="des_control" class="form-control form-control-sm" placeholder="Desempeño (opcional)">
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save"></i> Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulario Legajo -->
                    <div class="collapse mb-4" id="formularioLegajo">
                        <div class="card card-body border border-primary">
                            <form action="{{ route('herramientas_asistencia.guardar_legajo') }}" method="POST" class="row g-2">
                                @csrf
                                <div class="col-md-3">
                                    <input type="text" name="legajo" class="form-control form-control-sm" placeholder="Legajo" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="nombre_legajo" class="form-control form-control-sm" placeholder="Nombre" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="dependencia_usuario" id="dep_legajo" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Dependencia</option>
                                        @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                            <option value="{{ $dep }}">{{ strtoupper($dep) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="desempenio_usuario" id="des_legajo" class="form-control form-control-sm" placeholder="Desempeño (opcional)">
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulario Reloj -->
                    <div class="collapse mb-4" id="formularioReloj">
                        <div class="card card-body border border-warning">
                            <form action="{{ route('herramientas_asistencia.guardar_reloj') }}" method="POST" class="row g-2">
                                @csrf
                                <div class="col-md-4">
                                    <input type="text" name="reloj" class="form-control form-control-sm" placeholder="Nombre/Identificador del Reloj" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="dependencia_usuario" id="dep_reloj" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Dependencia</option>
                                        @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                            <option value="{{ $dep }}">{{ strtoupper($dep) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="desempenio_usuario" id="des_reloj" class="form-control form-control-sm" placeholder="Desempeño (opcional)">
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-save"></i> Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabla Usuarios de Control -->
                    <h6 class="text-primary mt-4">Usuarios de Control de Asistencia</h6>
                    @if(isset($usuarios_control) && count($usuarios_control))
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>CUIL</th>
                                    <th>Dependencia</th>
                                    <th>Desempeño</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios_control as $u)
                                <tr>
                                    <td>{{ $u->nombre_usuario }}</td>
                                    <td>{{ $u->cuil_usuario }}</td>
                                    <td>{{ $u->dependencia_usuario }}</td>
                                    <td>{{ $u->desempenio_usuario ?? '-' }}</td>
                                    <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-control" 
                                            data-id="{{ $u->id }}"
                                            data-nombre="{{ $u->nombre_usuario }}"
                                            data-cuil="{{ $u->cuil_usuario }}"
                                            data-dependencia="{{ $u->dependencia_usuario }}"
                                            data-desempenio="{{ $u->desempenio_usuario }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('herramientas_asistencia.eliminar_control', $u->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">No se encontraron usuarios de control para la dependencia seleccionada.</div>
                    @endif

                    <!-- Tabla Relojes Asignados -->
                    <h6 class="text-primary mt-4">Relojes Asignados</h6>
                    @if(isset($relojes) && count($relojes))
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Reloj</th>
                                    <th>Dependencia</th>
                                    <th>Desempeño</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($relojes as $r)
                                <tr>
                                    <td>{{ $r->reloj }}</td>
                                    <td>{{ $r->dependencia_usuario }}</td>
                                    <td>{{ $r->desempenio_usuario ?? '-' }}</td>
                                    <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-reloj" 
                                            data-id="{{ $r->id }}"
                                            data-reloj="{{ $r->reloj }}"
                                            data-dependencia="{{ $r->dependencia_usuario }}"
                                            data-desempenio="{{ $r->desempenio_usuario }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('herramientas_asistencia.eliminar_reloj', $r->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este reloj?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">No se encontraron relojes asignados para la dependencia seleccionada.</div>
                    @endif

                    <!-- Tabla Legajos Asignados -->
                    <h6 class="text-primary mt-4">Legajos Asignados</h6>
                    @if(isset($legajos) && count($legajos))
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Legajo</th>
                                    <th>Nombre</th>
                                    <th>Dependencia</th>
                                    <th>Desempeño</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($legajos as $l)
                                <tr>
                                    <td>{{ $l->legajo }}</td>
                                    <td>{{ $l->nombre_legajo }}</td>
                                    <td>{{ $l->dependencia_usuario }}</td>
                                    <td>{{ $l->desempenio_usuario ?? '-' }}</td>
                                    <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-legajo" 
                                            data-id="{{ $l->id }}"
                                            data-legajo="{{ $l->legajo }}"
                                            data-nombre="{{ $l->nombre_legajo }}"
                                            data-dependencia="{{ $l->dependencia_usuario }}"
                                            data-desempenio="{{ $l->desempenio_usuario }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('herramientas_asistencia.eliminar_legajo', $l->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este legajo?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">No se encontraron legajos asignados para la dependencia seleccionada.</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edición Control -->
<div class="modal fade" id="modalEditControl" tabindex="-1" aria-labelledby="modalEditControlLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditControlLabel">Editar Usuario de Control</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditControl" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre_usuario" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre_usuario" name="nombre_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cuil_usuario" class="form-label">CUIL</label>
                        <input type="text" class="form-control" id="edit_cuil_usuario" name="cuil_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dependencia_usuario" class="form-label">Dependencia</label>
                        <select class="form-select" id="edit_dependencia_usuario" name="dependencia_usuario" required>
                            @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                <option value="{{ $dep }}">{{ strtoupper($dep) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_desempenio_usuario" class="form-label">Desempeño</label>
                        <input type="text" class="form-control" id="edit_desempenio_usuario" name="desempenio_usuario">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edición Legajo -->
<div class="modal fade" id="modalEditLegajo" tabindex="-1" aria-labelledby="modalEditLegajoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLegajoLabel">Editar Legajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditLegajo" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_legajo" class="form-label">Legajo</label>
                        <input type="text" class="form-control" id="edit_legajo" name="legajo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nombre_legajo" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre_legajo" name="nombre_legajo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dependencia_legajo" class="form-label">Dependencia</label>
                        <select class="form-select" id="edit_dependencia_legajo" name="dependencia_usuario" required>
                            @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                <option value="{{ $dep }}">{{ strtoupper($dep) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_desempenio_legajo" class="form-label">Desempeño</label>
                        <input type="text" class="form-control" id="edit_desempenio_legajo" name="desempenio_usuario">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edición Reloj -->
<div class="modal fade" id="modalEditReloj" tabindex="-1" aria-labelledby="modalEditRelojLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditRelojLabel">Editar Reloj</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditReloj" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_reloj" class="form-label">Reloj</label>
                        <input type="text" class="form-control" id="edit_reloj" name="reloj" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dependencia_reloj" class="form-label">Dependencia</label>
                        <select class="form-select" id="edit_dependencia_reloj" name="dependencia_usuario" required>
                            @foreach(['enet', 'sbya', 'sgrl', 'dgp', 'efme', 'srii', 'sext', 'siyp', 'saca', 'earq', 'sinf'] as $dep)
                                <option value="{{ $dep }}">{{ strtoupper($dep) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_desempenio_reloj" class="form-label">Desempeño</label>
                        <input type="text" class="form-control" id="edit_desempenio_reloj" name="desempenio_usuario">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Controlar campos de desempeño
    const combos = [
        {dep: 'dependencia', des: 'desempenio'},
        {dep: 'dep_control', des: 'des_control'},
        {dep: 'dep_legajo', des: 'des_legajo'},
        {dep: 'dep_reloj', des: 'des_reloj'},
        {dep: 'edit_dependencia_usuario', des: 'edit_desempenio_usuario'},
        {dep: 'edit_dependencia_legajo', des: 'edit_desempenio_legajo'},
        {dep: 'edit_dependencia_reloj', des: 'edit_desempenio_reloj'}
    ];

    combos.forEach(function(par) {
        const dep = document.getElementById(par.dep);
        const des = document.getElementById(par.des);

        function controlar() {
            if (dep && des) {
                if (dep.value === 'efme') {
                    des.disabled = false;
                    des.placeholder = 'PRIM, SECU o INIC';
                } else if (dep.value === 'sgrl') {
                    des.disabled = false;
                    des.placeholder = 'Sólo DGOM';
                } else {
                    des.disabled = true;
                    des.value = '';
                    des.placeholder = 'Desempeño (opcional)';
                }
            }
        }

        if (dep) {
            dep.addEventListener('change', controlar);
            controlar();
        }
    });

    // Manejar edición de control
    document.querySelectorAll('.edit-control').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const form = document.getElementById('formEditControl');
            form.action = `/herramientas_asistencia/actualizar_control/${id}`;
            
            document.getElementById('edit_nombre_usuario').value = this.getAttribute('data-nombre');
            document.getElementById('edit_cuil_usuario').value = this.getAttribute('data-cuil');
            document.getElementById('edit_dependencia_usuario').value = this.getAttribute('data-dependencia');
            document.getElementById('edit_desempenio_usuario').value = this.getAttribute('data-desempenio') || '';
            
            // Disparar evento change para actualizar el campo desempeño
            const event = new Event('change');
            document.getElementById('edit_dependencia_usuario').dispatchEvent(event);
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditControl'));
            modal.show();
        });
    });

    // Manejar edición de legajo
    document.querySelectorAll('.edit-legajo').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const form = document.getElementById('formEditLegajo');
            form.action = `/herramientas_asistencia/actualizar_legajo/${id}`;
            
            document.getElementById('edit_legajo').value = this.getAttribute('data-legajo');
            document.getElementById('edit_nombre_legajo').value = this.getAttribute('data-nombre');
            document.getElementById('edit_dependencia_legajo').value = this.getAttribute('data-dependencia');
            document.getElementById('edit_desempenio_legajo').value = this.getAttribute('data-desempenio') || '';
            
            // Disparar evento change para actualizar el campo desempeño
            const event = new Event('change');
            document.getElementById('edit_dependencia_legajo').dispatchEvent(event);
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditLegajo'));
            modal.show();
        });
    });

    // Manejar edición de reloj
    document.querySelectorAll('.edit-reloj').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const form = document.getElementById('formEditReloj');
            form.action = `/herramientas_asistencia/actualizar_reloj/${id}`;
            
            document.getElementById('edit_reloj').value = this.getAttribute('data-reloj');
            document.getElementById('edit_dependencia_reloj').value = this.getAttribute('data-dependencia');
            document.getElementById('edit_desempenio_reloj').value = this.getAttribute('data-desempenio') || '';
            
            // Disparar evento change para actualizar el campo desempeño
            const event = new Event('change');
            document.getElementById('edit_dependencia_reloj').dispatchEvent(event);
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditReloj'));
            modal.show();
        });
    });
});
</script>
@endsection