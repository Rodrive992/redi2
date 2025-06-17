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
                            <i class="bi bi-inbox-fill me-2"></i>Registro Mesa de Entrada
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
                       
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registrarModal">
                            <i class="bi bi-plus-circle me-1"></i> Nuevo Registro
                        </button>
                       
                        
                        
                        <div class="col-md-4">
                            <form action="{{ route('herramientas.mesa_entrada') }}" method="GET">
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
                                    @if(auth()->user()->desempenio === 'mesa_entrada')
                                    <th width="15%">Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mesas as $mesa)
                                <tr>
                                    <td>{{ $mesa->id }}</td>
                                    <td>{{ $mesa->usuario }}</td>
                                    <td>{{ $mesa->entrada }}</td>
                                    <td>{{ $mesa->nombre }}</td>
                                    <td>{{ $mesa->dependencia }}</td>
                                    <td>{{ $mesa->entregado_a }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $mesa->estado == 'Pendiente' ? 'warning text-dark' : 'success' }}">
                                            {{ $mesa->estado }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($mesa->fecha)->format('d/m/Y') }}</td>
                                    @if(auth()->user()->desempenio === 'mesa_entrada')
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary px-2 py-1 editar-btn" 
                                                title="Editar" data-id="{{ $mesa->id }}" data-bs-toggle="modal" 
                                                data-bs-target="#editarModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger px-2 py-1 eliminar-btn" 
                                                title="Eliminar" data-id="{{ $mesa->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Mostrando {{ $mesas->firstItem() }} a {{ $mesas->lastItem() }} de {{ $mesas->total() }} registros
                        </div>
                        <div>
                            {{ $mesas->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Registrar -->
<div class="modal fade" id="registrarModal" tabindex="-1" aria-labelledby="registrarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="registrarModalLabel">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Registro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mesa.registrar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="entrada" class="form-label small text-muted">Número de Entrada</label>
                        <input type="text" class="form-control form-control-sm" id="entrada" name="entrada" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label small text-muted">Nombre del Documento</label>
                        <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="dependencia" class="form-label small text-muted">Dependencia</label>
                        <input type="text" class="form-control form-control-sm" id="dependencia" name="dependencia" required>
                    </div>
                    <div class="mb-3">
                        <label for="entregado_a" class="form-label small text-muted">Entregado a</label>
                        <select class="form-select form-select-sm" id="entregado_a" name="entregado_a" required>
                            <option value="">Seleccionar destinatario...</option>
                            <option value="Ciro">Ciro</option>
                            <option value="Renato">Renato</option>
                            <option value="Ceci">Ceci</option>
                            <option value="Camila">Camila</option>
                            <option value="Rodri">Rodrigo</option>
                            <option value="Pato">Patricio</option>
                            <option value="Flavia">Flavia</option>
                            <option value="Ale">Ale</option>
                            <option value="Adri">Adriana</option>
                            <option value="Rosana">Rosana</option>
                            <option value="Laura">Laura</option>
                            <option value="Vale">Valeria</option>
                            <option value="Nico">Nico</option>
                        </select>
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
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editarModalLabel">
                    <i class="bi bi-pencil me-1"></i> Editar Registro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editarForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar_id">
                    <div class="mb-3">
                        <label for="editar_entrada" class="form-label small text-muted">Número de Entrada</label>
                        <input type="text" class="form-control form-control-sm" id="editar_entrada" name="entrada" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label small text-muted">Nombre del Documento</label>
                        <input type="text" class="form-control form-control-sm" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_dependencia" class="form-label small text-muted">Dependencia</label>
                        <input type="text" class="form-control form-control-sm" id="editar_dependencia" name="dependencia" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_entregado_a" class="form-label small text-muted">Entregado a</label>
                        <select class="form-select form-select-sm" id="editar_entregado_a" name="entregado_a" required>
                            <option value="">Seleccionar destinatario...</option>
                            <option value="Ciro">Ciro</option>
                            <option value="Renato">Renato</option>
                            <option value="Ceci">Ceci</option>
                            <option value="Camila">Camila</option>
                            <option value="Rodri">Rodrigo</option>
                            <option value="Pato">Patricio</option>
                            <option value="Flavia">Flavia</option>
                            <option value="Ale">Ale</option>
                            <option value="Adri">Adriana</option>
                            <option value="Rosana">Rosana</option>
                            <option value="Laura">Laura</option>
                            <option value="Vale">Valeria</option>
                            <option value="Nico">Nico</option>
                        </select>
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
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Configuración de Toast
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Función para manejar la edición
    async function manejarEdicion(docId) {
        try {
            // Obtener datos del documento
            const response = await fetch(`/mesa/editar/${docId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error('Error al cargar datos para editar');
            }
            
            const data = await response.json();
            
            // Llenar el modal con los datos
            document.getElementById('editar_id').value = data.id;
            document.getElementById('editar_entrada').value = data.entrada;
            document.getElementById('editar_nombre').value = data.nombre;
            document.getElementById('editar_dependencia').value = data.dependencia;
            
            // Seleccionar el destinatario correcto
            const select = document.getElementById('editar_entregado_a');
            select.value = data.entregado_a;
            
            // Configurar la acción del formulario
            document.getElementById('editarForm').action = `/mesa/actualizar/${data.id}`;
            
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('editarModal'));
            modal.show();
            
        } catch (error) {
            console.error('Error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al cargar datos',
                text: error.message
            });
        }
    }

    // Función para manejar la actualización
    async function manejarActualizacion(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const docId = formData.get('id');
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Error en el servidor');
            }
            
            Toast.fire({
                icon: 'success',
                title: data.message
            });
            
            // Cerrar el modal y recargar
            const modal = bootstrap.Modal.getInstance(document.getElementById('editarModal'));
            modal.hide();
            
            setTimeout(() => location.reload(), 1000);
            
        } catch (error) {
            console.error('Error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: error.message
            });
        }
    }

    // Función para manejar eliminación
    async function manejarEliminacion(docId) {
        try {
            const result = await Swal.fire({
                title: '¿Eliminar registro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });
            
            if (result.isConfirmed) {
                const response = await fetch(`/mesa/eliminar/${docId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Error en el servidor');
                }
                
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
                
                setTimeout(() => location.reload(), 1000);
            }
            
        } catch (error) {
            console.error('Error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al eliminar',
                text: error.message
            });
        }
    }

    // Eventos cuando el DOM está listo
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar el evento de envío del formulario de edición
        document.getElementById('editarForm').addEventListener('submit', manejarActualizacion);
        
        // Delegación de eventos para los botones
        document.addEventListener('click', function(e) {
            // Manejar botón Editar
            if (e.target.closest('.editar-btn')) {
                e.preventDefault();
                const docId = e.target.closest('.editar-btn').dataset.id;
                manejarEdicion(docId);
            }
            
            // Manejar botón Eliminar
            if (e.target.closest('.eliminar-btn')) {
                e.preventDefault();
                const docId = e.target.closest('.eliminar-btn').dataset.id;
                manejarEliminacion(docId);
            }
        });
    });
</script>
@endsection