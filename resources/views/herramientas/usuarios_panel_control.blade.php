@extends('layouts.app')

@section('title', 'Usuarios - Panel de Control')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Tarjeta de Título y Herramientas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-people-fill me-2"></i>Usuarios - Panel de Control
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
                            <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
                        </button>

                        <div class="col-md-4">
                            <form action="{{ route('herramientas.usuarios_panel_control') }}" method="GET">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control form-control-sm"
                                           placeholder="Buscar por nombre, CUIL o dependencia" value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="4%">ID</th>
                                    <th width="18%">Nombre</th>
                                    <th width="16%">Email</th>
                                    <th width="14%">CUIL</th>
                                    <th width="12%">Dependencia</th>
                                    <th width="14%">Desempeño</th>
                                    <th width="12%">Permiso</th>
                                    <th width="16%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $u)
                                <tr>
                                    <td>{{ $u->id }}</td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->cuil }}</td>
                                    <td>{{ $u->dependencia }}</td>
                                    <td>{{ $u->desempenio }}</td>
                                    <td>{{ $u->permiso }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary px-2 py-1"
                                                title="Editar"
                                                onclick="cargarEdicion({{ $u->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger px-2 py-1"
                                                title="Eliminar"
                                                onclick="confirmarEliminacion({{ $u->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No hay usuarios para mostrar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            @if($usuarios->total() > 0)
                                Mostrando {{ $usuarios->firstItem() }} a {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
                            @endif
                        </div>
                        <div>
                            {{ $usuarios->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal: Crear Usuario -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formCrearUsuario" action="{{ route('usuarios.crear') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="crearUsuarioModalLabel">
                    <i class="bi bi-person-plus me-1"></i> Crear Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label small text-muted">Nombre</label>
                    <input type="text" name="name" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Email</label>
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Opcional">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">CUIL</label>
                    <input type="text" name="cuil" class="form-control form-control-sm" required placeholder="20XXXXXXXXX">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Dependencia</label>
                    <input type="text" name="dependencia" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Desempeño</label>
                    <input type="text" name="desempenio" class="form-control form-control-sm" placeholder="">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Permiso</label>
                    <input type="text" name="permiso" id="permiso" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditarUsuario" action="{{ route('usuarios.actualizar') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editarUsuarioModalLabel">
                    <i class="bi bi-pencil me-1"></i> Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">

                <div class="mb-2">
                    <label class="form-label small text-muted">Nombre</label>
                    <input type="text" name="name" id="edit_name" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control form-control-sm" placeholder="Opcional">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">CUIL</label>
                    <input type="text" name="cuil" id="edit_cuil" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Dependencia</label>
                    <input type="text" name="dependencia" id="edit_dependencia" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Desempeño</label>
                    <input type="text" name="desempenio" id="edit_desempenio" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Permiso</label>
                    <input type="text" name="permiso" id="edit_permiso" class="form-control form-control-sm">
                </div>
                <hr class="my-2">
                <div class="mb-2">
                    <label class="form-label small text-muted">Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" name="password" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-sm">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toast
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 1800, timerProgressBar: true
    });

    // Crear Usuario (AJAX)
    document.getElementById('formCrearUsuario')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;

        const resp = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: new FormData(form)
        });

        const data = await resp.json().catch(() => ({}));

        if (resp.ok) {
            Toast.fire({ icon: 'success', title: data.message || 'Usuario creado' });
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('crearUsuarioModal'));
            modal.hide();
            setTimeout(() => location.reload(), 900);
        } else {
            Toast.fire({ icon: 'error', title: data.message || 'Error al crear usuario' });
        }
    });

    // Cargar datos en modal de edición
    async function cargarEdicion(id) {
        const url = "{{ route('herramientas.usuarios_panel_control') }}?load=" + id;
        try {
            const resp = await fetch(url, { headers: { 'Accept': 'application/json' }});
            if (!resp.ok) throw new Error('No se pudieron cargar los datos');
            const u = await resp.json();

            // Setear campos
            document.getElementById('edit_id').value = u.id;
            document.getElementById('edit_name').value = u.name ?? '';
            document.getElementById('edit_email').value = u.email ?? '';
            document.getElementById('edit_cuil').value = u.cuil ?? '';
            document.getElementById('edit_dependencia').value = u.dependencia ?? '';
            document.getElementById('edit_desempenio').value = u.desempenio ?? '';
            document.getElementById('edit_permiso').value = u.permiso ?? '';


            // Abrir modal
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editarUsuarioModal')).show();
        } catch (err) {
            Toast.fire({ icon: 'error', title: err.message });
        }
    }

    // Editar Usuario (AJAX)
    document.getElementById('formEditarUsuario')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;

        const resp = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: new FormData(form)
        });
        const data = await resp.json().catch(() => ({}));

        if (resp.ok) {
            Toast.fire({ icon: 'success', title: data.message || 'Usuario actualizado' });
            bootstrap.Modal.getInstance(document.getElementById('editarUsuarioModal')).hide();
            setTimeout(() => location.reload(), 900);
        } else {
            Toast.fire({ icon: 'error', title: data.message || 'Error al actualizar' });
        }
    });

    // Eliminar Usuario (AJAX)
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const resp = await fetch("{{ route('usuarios.eliminar') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                const data = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    Toast.fire({ icon: 'success', title: data.message || 'Usuario eliminado' });
                    setTimeout(() => location.reload(), 900);
                } else {
                    Toast.fire({ icon: 'error', title: data.message || 'No se pudo eliminar' });
                }
            }
        });
    }
</script>
@endsection
