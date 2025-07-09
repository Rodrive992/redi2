<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="bi bi-share"></i> REDI 2.0
        </a>

        <div class="d-flex flex-column text-center text-white mx-auto">
            <span class="fw-medium">
                {{ auth()->user()->name }} | {{ auth()->user()->dependencia }}
            </span>
            <span id="live-clock" class="small">
                {{ now()->format('d/m/Y H:i:s') }}
            </span>
        </div>

        <div class="d-flex align-items-center">
            <!-- Condicionar el componente de notificaciones solo si la dependencia es 'dgp' -->
            @if(auth()->user()->dependencia === 'dgp')
                <x-notifications.badge />
            @endif

            <!-- Añadimos el nuevo condicional para autorizadores -->
            @if(auth()->user()->permiso === 'autorizar')
                <x-notifications.real-prestacion-badge />
            @endif
            
            <!-- Botón de logout directo (sin dropdown) -->
            <form method="POST" action="{{ route('logout') }}" class="ms-3">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </button>
            </form>
        </div>
    </div>
</nav>