<div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="herramientasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-list"></i> Menú de Herramientas
    </button>
    <ul class="dropdown-menu" aria-labelledby="herramientasDropdown">
        @foreach([
            //'mesa_entrada' => ['icon' => 'bi-inbox-fill', 'text' => 'Mesa de Entrada'],
            //'compatibilidad' => ['icon' => 'bi-people-fill', 'text' => 'Compatibilidad'],
            //'certificados' => ['icon' => 'bi-file-earmark-text', 'text' => 'Certificados'],
            //'procedimientos' => ['icon' => 'bi-journal-text', 'text' => 'Procedimientos'],
            'real_prestacion_externo' => ['icon' => 'bi-upload', 'text' => 'Cargar Real Prestación'],
            'real_prestacion_historial_externo' => ['icon' => 'bi-journal-text', 'text' => 'Historial Real Prestacion'],
            'asistencia_externo' => ['icon' => 'bi-calendar-check', 'text' => 'Asistencia'],
            //'vencimientos' => ['icon' => 'bi-clock-history', 'text' => 'Vencimientos'],
            
            //'consultar_bases' => ['icon' => 'bi-clipboard-check', 'text' => 'Consultar Bases'],
            //'carga_bases' => ['icon' => 'bi-database', 'text' => 'Carga de Bases'],
            //'suma_horarios' => ['icon' => 'bi-calculator', 'text' => 'Suma Horarios']
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