@extends('layouts.app')

@section('title', 'REDI 2.0 - Panel DGP')

@section('content')
<div class="container-fluid px-0">
    <!-- Contenido principal -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title text-primary mb-0">
                            <i class="bi bi-speedometer2 me-2"></i>Panel Principal
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill"></i> Bienvenido al sistema de gestión documental
                        </div>

                        <!-- Widgets/Estadísticas -->
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Documentos Pendientes</h5>
                                        @php
                                            $countPendientes = DB::table('mesa')
                                                ->where('entregado_a', auth()->user()->name)
                                                ->where('estado', 'Pendiente')
                                                ->count();
                                        @endphp
                                        <p class="display-4 text-primary">{{ $countPendientes }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Agrega más widgets según necesites -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- Reloj en tiempo real -->
<script>
    function updateClock() {
        const now = new Date();
        const options = { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false,
            timeZone: 'America/Argentina/Buenos_Aires'
        };
        document.getElementById('live-clock').textContent = now.toLocaleDateString('es-AR', options);
    }
    setInterval(updateClock, 1000);
    updateClock(); // Ejecutar inmediatamente
</script>

<!-- Manejo de notificaciones -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Recibir todos
        document.getElementById('recibirTodos')?.addEventListener('click', function() {
            if (confirm('¿Confirmar recepción de todos los documentos?')) {
                fetch("{{ route('mesa.recibir') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });

        // Reenviar todos
        document.getElementById('reenviarTodos')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Reenviar documentos',
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
                    fetch("{{ route('mesa.reenviar') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
@endsection