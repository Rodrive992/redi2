    @extends('layouts.app')

    @section('title', 'REDI 2.0 - Panel DGP')

    @section('content')
    <div class="container-fluid px-0">
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

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Documentos Pendientes</h5>
                                            @php
                                                use App\Models\Mesa;
                                                $countPendientes = Mesa::pendientesDe(auth()->user()->name)->count();
                                            @endphp
                                            <p class="display-4 text-primary">{{ $countPendientes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
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
        updateClock();
    </script>
    @endsection