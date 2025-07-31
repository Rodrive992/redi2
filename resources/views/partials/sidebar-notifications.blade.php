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
            <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                <div class="me-3">
                    <h6 class="mb-1 fw-bold text-dark">{{ $doc->entrada }}</h6>
                    <p class="mb-1 text-muted small">De: <strong>{{ $doc->dependencia }}</strong></p>
                    <small class="text-muted d-block">ID: {{ $doc->id }}</small>
                    <small class="text-muted fw-bold">Nombre: {{ $doc->nombre }}</small> <!-- Aquí se agrega negrita -->
                    @if($doc->observaciones)
                        <small class="text-muted d-block mt-2">Obs: {{ $doc->observaciones }}</small>
                    @endif
                </div>
                <small class="text-muted">{{ \Carbon\Carbon::parse($doc->fecha)->format('d/m/Y') }}</small>
            </div>

            <!-- Alineación de los botones a la derecha -->
            <div class="d-flex gap-2 mt-2 mb-4 justify-content-end">
                <button class="btn btn-sm btn-success recibir-doc" data-id="{{ $doc->id }}">
                    <i class="bi bi-check-circle"></i> Recibir
                </button>
                <button class="btn btn-sm btn-warning reenviar-doc" data-id="{{ $doc->id }}">
                    <i class="bi bi-arrow-right-circle"></i> Reenviar
                </button>
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
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de Toast
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
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

            if (!response.ok) throw new Error(data.message || 'Error en el servidor');

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

    // Función para manejar reenvío
    async function manejarReenvio(docId) {
        try {
            const { value: destino, isDismissed } = await Swal.fire({
                title: 'Reenviar documento',
                html: `
                    <div class="mb-3">
                        <label for="destino" class="form-label">Seleccione destinatario</label>
                        <select id="destino" class="form-select">
                            <option value="" disabled selected>Seleccione un destinatario</option>
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
                preConfirm: () => {
                    const destinoValue = Swal.getPopup().querySelector('#destino').value;
                    if (!destinoValue) {
                        Swal.showValidationMessage('Debe seleccionar un destinatario');
                        return false;
                    }
                    return destinoValue;
                },
                didOpen: () => {
                    const select = Swal.getPopup().querySelector('#destino');
                    $(select).select2({
                        dropdownParent: Swal.getPopup(),
                        width: '100%',
                        placeholder: 'Seleccione destinatario'
                    });
                }
            });

            if (isDismissed) {
                Toast.fire({
                    icon: 'info',
                    title: 'Reenvío cancelado'
                });
                return;
            }

            if (destino) {
                const response = await fetch(`/mesa/reenviar/${docId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ destino })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Error en el servidor');

                Toast.fire({
                    icon: 'success',
                    title: data.message
                });

                setTimeout(() => location.reload(), 1000);
            }

        } catch (error) {
            Toast.fire({
                icon: 'error',
                title: 'Error al reenviar',
                text: error.message
            });
        }
    }

    // Delegar eventos de click para los botones de "Recibir" y "Reenviar"
    document.getElementById('notificationsSidebar').addEventListener('click', function(e) {
        const btnRecibir = e.target.closest('.recibir-doc');
        const btnReenviar = e.target.closest('.reenviar-doc');

        if (btnRecibir) {
            e.preventDefault();
            const docId = btnRecibir.dataset.id;

            Swal.fire({
                title: '¿Confirmar recepción?',
                text: `Estás por marcar el documento ${docId} como recibido.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-check-circle"></i> Recibir',
                cancelButtonText: '<i class="bi bi-x-circle"></i> Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    manejarRecepcion(docId);
                }
            });
        }

        if (btnReenviar) {
            e.preventDefault();
            const docId = btnReenviar.dataset.id;
            manejarReenvio(docId);
        }
    });
});
</script>