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
                                                    data-bs-target="#editarModal" onclick="manejarEdicion({{ $mesa->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger px-2 py-1 eliminar-btn" 
                                                    title="Eliminar" data-id="{{ $mesa->id }}" onclick="manejarEliminacion({{ $mesa->id }})">
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
                    <i class="bi bi-plus-circle me-1"></i> Registro Mesa de Entrada
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mesa.registrar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="entrada" class="form-label small text-muted">Entrada</label>
                        <input type="text" class="form-control form-control-sm" id="entrada" name="entrada" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label small text-muted">Nombre</label>
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Configuración de Toast (con verificación de existencia)
    if (typeof Toast === 'undefined') {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    }
        // Función para manejar el envío del formulario de registro
        document.addEventListener('DOMContentLoaded', function() {
        
        const formRegistrar = document.querySelector('form[action="{{ route('mesa.registrar') }}"]');
        
        formRegistrar.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evitar el envío del formulario de manera tradicional

            const formData = new FormData(formRegistrar);

            try {
                const response = await fetch(formRegistrar.action, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error('Error al crear el registro');
                }

                console.log('Registro creado correctamente:', data);

                // Mostrar el mensaje con Toast
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });

                // Recargar la página o redirigir, dependiendo de la lógica
                setTimeout(() => {
                    location.reload(); // Recargar la página después de mostrar el mensaje
                }, 1000);
            } catch (error) {
                console.error('Error al registrar el registro:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al crear el registro',
                    text: error.message
                });
            }
        });
    });

    // Función para manejar la edición
    function manejarEdicion(docId) {
        console.log(`Iniciando edición para documento ID: ${docId}`);
        
        fetch(`/mesa/editar/${docId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Llenar el formulario de edición
            document.getElementById('editar_id').value = data.id;
            document.getElementById('editar_entrada').value = data.entrada;
            document.getElementById('editar_nombre').value = data.nombre;
            document.getElementById('editar_dependencia').value = data.dependencia;
            document.getElementById('editar_entregado_a').value = data.entregado_a;
            
            // Configurar la acción del formulario
            document.getElementById('editarForm').action = `/mesa/actualizar/${data.id}`;
            
            // Mostrar el modal usando el método nativo de Bootstrap
            var modalEl = document.getElementById('editarModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        })
        .catch(error => {
            console.error('Error en manejarEdicion:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al cargar datos',
                text: error.message
            });
        });
    }

    // Función para manejar la eliminación
    function manejarEliminacion(docId) {
        Swal.fire({
            title: '¿Confirmar eliminación?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(`Eliminando documento ID: ${docId}`);
                
                fetch(`/mesa/eliminar/${docId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('Error en manejarEliminacion:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al eliminar',
                        text: error.message
                    });
                });
            }
        });
    }

    // Configuración cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM completamente cargado');
        
        // Configurar el formulario de edición
        const editarForm = document.getElementById('editarForm');
        if (editarForm) {
            editarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Enviando formulario de edición');
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al actualizar');
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    
                    // Cerrar el modal usando el método nativo
                    var modalEl = document.getElementById('editarModal');
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                    
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('Error al enviar formulario:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al guardar cambios',
                        text: error.message
                    });
                });
            });
        }
        
        // Eliminar cualquier event listener previo del botón Cancelar
        const cancelButton = document.querySelector('#editarModal .btn-outline-secondary');
        if (cancelButton) {
            // Clonar el botón para eliminar listeners
            const newCancelButton = cancelButton.cloneNode(true);
            cancelButton.parentNode.replaceChild(newCancelButton, cancelButton);
            
            // El cierre del modal ahora se manejará automáticamente por Bootstrap
            // gracias al atributo data-bs-dismiss="modal"
        }
    });
</script>
@endsection