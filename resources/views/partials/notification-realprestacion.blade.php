<div class="offcanvas offcanvas-end" tabindex="-1" id="realPrestacionSidebar" aria-labelledby="realPrestacionSidebarLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title" id="realPrestacionSidebarLabel">
            <i class="bi bi-file-earmark-text-fill me-2"></i>
            Real Prestaciones Pendientes
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @php
        $pendientes = App\Models\ArchivosRealPrestacion::where('estado', 'pendiente')
            ->when(auth()->user()->dependencia !== 'admin', function($query) {
                return $query->where('dependencia', auth()->user()->dependencia);
            })
            ->orderBy('fecha_subida', 'desc')
            ->get();
        @endphp

        @if($pendientes->count() > 0)
            <div class="list-group">
                @foreach($pendientes as $archivo)
                <a href="{{ route('herramientas.real_prestacion_historial_externo') }}" 
                   class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $archivo->nombre_archivo }}</h6>
                        <small>{{ $archivo->fecha_subida->format('d/m/Y') }}</small>
                    </div>
                    <p class="mb-1 small text-muted">
                        {{ $archivo->dependencia }} - {{ $archivo->mes }}/{{ $archivo->ano }}
                    </p>
                </a>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('herramientas.real_prestacion_historial_externo') }}" 
                   class="btn btn-primary w-100">
                    Ver todos <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        @else
            <div class="alert alert-success mb-0">
                <i class="bi bi-check-circle-fill me-2"></i>
                No hay real prestaciones pendientes
            </div>
        @endif
    </div>
</div>