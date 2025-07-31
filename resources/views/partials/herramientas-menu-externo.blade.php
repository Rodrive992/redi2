@php
    $desempenio = auth()->user()->desempenio ?? null;

    $items = [
        'real_prestacion_externo' => ['icon' => 'bi-upload', 'text' => 'Cargar Real Prestación'],
        'real_prestacion_historial_externo' => ['icon' => 'bi-journal-text', 'text' => 'Historial Real Prestacion'],
    ];

    // Condición para la ruta de Asistencia
    $asistenciaRoute = $desempenio === 'unai' ? 'asistencia_externo_uai' : 'asistencia_externo';
    $items[$asistenciaRoute] = ['icon' => 'bi-calendar-check', 'text' => 'Asistencia'];
@endphp

<div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="herramientasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-list"></i> Menú de Herramientas
    </button>
    <ul class="dropdown-menu" aria-labelledby="herramientasDropdown">
        @foreach($items as $route => $options)
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route("herramientas.$route") }}">
                    <i class="bi {{ $options['icon'] }}"></i>
                    <span>{{ $options['text'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>