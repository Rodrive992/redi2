@php
use App\Models\Mesa;
$pendientes = Mesa::pendientesDe(auth()->user()->name)->get();
@endphp

<div class="offcanvas offcanvas-start" tabindex="-1" id="notificationsSidebar">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title">Documentación Pendiente ({{ $pendientes->count() }})</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @if($pendientes->count() > 0)
        <div class="list-group list-group-flush">
            @foreach($pendientes as $doc)
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between align-items-start">
                    <div class="me-3">
                        <h6 class="mb-1 small">{{ $doc->entrada }}</h6>
                        <p class="mb-1 small">De: {{ $doc->dependencia }}</p>
                        <small class="text-muted d-block">ID: {{ $doc->id }}</small>
                        <small class="text-muted">Nombre: {{ $doc->nombre }}</small>
                        @if($doc->observaciones)
                            <small class="text-muted d-block mt-1">Obs: {{ $doc->observaciones }}</small>
                        @endif
                    </div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($doc->fecha)->format('d/m/Y') }}</small>
                </div>
                
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-success recibir-doc" data-id="{{ $doc->id }}">
                        <i class="bi bi-check-circle"></i> Recibir
                    </button>
                    <button class="btn btn-sm btn-warning reenviar-doc" data-id="{{ $doc->id }}">
                        <i class="bi bi-arrow-right-circle"></i> Reenviar
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info m-3">
            No hay documentos pendientes
        </div>
        @endif
    </div>
</div>

<script>
// Configuración de Toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

// Función para manejar recepción
async function manejarRecepcion(docId) {
    try {
        const response = await fetch(`/mesa/recibir/${docId}`, {
            method: 'POST',
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
        
    } catch (error) {
        Toast.fire({
            icon: 'error',
            title: 'Error al recibir',
            text: error.message
        });
    }
}

// Función para manejar reenvío (versión mejorada)
async function manejarReenvio(docId) {
    const { value: formValues, isDismissed } = await Swal.fire({
        title: '<strong>Reenviar Documento</strong>',
        html: `
            <div class="mb-3">
                <label class="form-label">Destinatario</label>
                <select id="destino" class="form-select">
                    <option disabled selected value="">Seleccione un destinatario</option>
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
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-send"></i> Reenviar',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        customClass: {
            popup: 'rounded-3',
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger'
        },
        preConfirm: () => {
            const destino = Swal.getPopup().querySelector('#destino').value;
            if (!destino) {
                Swal.showValidationMessage('Debe seleccionar un destinatario');
                return false;
            }
            return { destino };
        }
    });

    // Si el usuario hizo clic en Cancelar
    if (isDismissed) {
        Toast.fire({
            icon: 'info',
            title: 'Reenvío cancelado'
        });
        return;
    }

    // Si confirmó el reenvío
    if (formValues) {
        try {
            const response = await fetch(`/mesa/reenviar/${docId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formValues)
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
            
        } catch (error) {
            Toast.fire({
                icon: 'error',
                title: 'Error al reenviar',
                text: error.message
            });
        }
    }
}

// Eventos cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Delegación de eventos
    document.addEventListener('click', function(e) {
        // Manejar botón Recibir
        if (e.target.closest('.recibir-doc')) {
            e.preventDefault();
            const docId = e.target.closest('.recibir-doc').dataset.id;
            
            Swal.fire({
                title: '¿Confirmar recepción?',
                text: `Estás por marcar el documento ${docId} como recibido`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, recibir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    manejarRecepcion(docId);
                }
            });
        }
        
        // Manejar botón Reenviar
        if (e.target.closest('.reenviar-doc')) {
            e.preventDefault();
            const docId = e.target.closest('.reenviar-doc').dataset.id;
            manejarReenvio(docId);
        }
    });
});
</script>