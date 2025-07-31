<div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="herramientasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-list"></i> Herramientas Asistencia
    </button>
    <ul class="dropdown-menu" aria-labelledby="herramientasDropdown">
        @foreach([
            'asistencia' => ['icon' => 'bi-people-fill', 'text' => 'Informe asistencia'],
            'panel_control' => ['icon' => 'bi bi-tools', 'text' => 'Panel de Control'],
            'calcular_horas' => ['icon' => 'bi bi-calculator', 'text' => 'Calcular Horas']
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