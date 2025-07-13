<div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="herramientasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-list"></i> Herramientas Real Prestación
    </button>
    <ul class="dropdown-menu" aria-labelledby="herramientasDropdown">
        @foreach([
            'real_prestacion' => ['icon' => 'bi-upload', 'text' => 'Cargar Real Prestación'],
            'real_prestacion_historial' => ['icon' => 'bi-journal-text', 'text' => 'Historial Real Prestacion'],
            'real_prestacion_control' => ['icon' => 'bi bi-pencil', 'text' => 'Control de Real Prestación'],
            
        ] as $route => $options)
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route("herramientas.$route") }}">
                    <i class="bi {{ $options['icon'] }}"></i>
                    <span>{{ $options['text'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>