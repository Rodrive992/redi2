@php
$pendientes = DB::table('mesa')
    ->where('entregado_a', auth()->user()->name)
    ->where('estado', 'Pendiente')
    ->orderBy('fecha', 'desc')
    ->get();
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
                        <h6 class="mb-1 small">{{ $doc->entrada }}</h6> <!-- Texto más pequeño -->
                        <p class="mb-1 small">De: {{ $doc->dependencia }}</p>
                        <small class="text-muted d-block">ID: {{ $doc->id }}</small>
                        <small class="text-muted">Nombre: {{ $doc->nombre }}</small>
                    </div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($doc->fecha)->format('d/m/Y') }}</small>
                </div>
                
                <!-- Botones por documento -->
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recibir documento individual
    document.querySelectorAll('.recibir-doc').forEach(btn => {
        btn.addEventListener('click', function() {
            const docId = this.getAttribute('data-id');
            if (confirm('¿Confirmar recepción de este documento?')) {
                fetch(`/mesa/recibir/${docId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                });
            }
        });
    });

    // Reenviar documento individual
    document.querySelectorAll('.reenviar-doc').forEach(btn => {
        btn.addEventListener('click', function() {
            const docId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Reenviar Documento',
                html: `
                    <input type="text" id="destino" class="swal2-input" placeholder="Destinatario" required>
                    <textarea id="observaciones" class="swal2-textarea" placeholder="Observaciones"></textarea>
                `,
                confirmButtonText: 'Reenviar',
                showCancelButton: true,
                preConfirm: () => {
                    return {
                        destino: document.getElementById('destino').value,
                        observaciones: document.getElementById('observaciones').value
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/mesa/reenviar/${docId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                    });
                }
            });
        });
    });
});
</script>
@endsection