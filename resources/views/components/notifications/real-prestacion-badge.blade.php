@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

$user = Auth::user();

// Si la dependencia es 'dgp', usar 'secf'; si no, usar la real
$dependenciaReal = ($user->dependencia === 'dgp') ? 'secf' : $user->dependencia;
$rutaHistorial = ($user->dependencia === 'dgp') 
        ? route('herramientas.real_prestacion_historial') 
        : route('herramientas.real_prestacion_historial_externo');

$count = DB::table('archivos_real_prestacion')
    ->where('estado', 'Pendiente')
    ->when($user->dependencia !== 'admin', function ($query) use ($dependenciaReal) {
        return $query->where('dependencia', $dependenciaReal);
    })
    ->count();
@endphp

<div class="position-relative me-3">
    <a href="{{ $rutaHistorial }}" 
       class="btn btn-link text-white p-0 position-relative"
       onclick="return showRpAlert({{ $count }})">
        <i class="bi bi-file-earmark-text-fill fs-4"></i>
        @if($count > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $count }}
            <span class="visually-hidden">Real prestaciones pendientes</span>
        </span>
        @endif
    </a>
</div>

<!-- Contenedor de Toasts - Parte superior central -->
<div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1100">
    <div id="rpToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-primary text-white">
            <strong class="me-auto">
                <i class="bi bi-file-earmark-text-fill"></i> Real Prestaciones
            </strong>
            <small class="text-white">Ahora</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            <div id="rpToastContent">
                <!-- Contenido dinámico se insertará aquí -->
            </div>
            <div class="mt-2 pt-2 border-top text-center">
                <a href="{{ $rutaHistorial }}" 
                   class="btn btn-sm btn-primary" id="rpToastButton">
                    <i class="bi bi-eye-fill me-1"></i> Ver
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" data-bs-dismiss="toast">
                    <i class="bi bi-x-lg me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showRpAlert(count) {
    const toastEl = document.getElementById('rpToast');
    const toast = new bootstrap.Toast(toastEl);
    const toastContent = document.getElementById('rpToastContent');
    const toastButton = document.getElementById('rpToastButton');
    
    // Configuración adicional para posición y animación
    toastEl.style.width = '400px';
    toastEl.style.maxWidth = '100%';
    
    if (count > 0) {
        toastContent.innerHTML = `
            <div class="alert alert-warning mb-0 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div>
                    <h6 class="mb-1">Tienes ${count} Real Prestación(es) pendientes</h6>
                    <p class="small mb-0">Documentos requieren tu autorización</p>
                </div>
            </div>
        `;
        toastButton.classList.remove('d-none');
    } else {
        toastContent.innerHTML = `
            <div class="alert alert-success mb-0 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div>
                    <h6 class="mb-1">Todo al día</h6>
                    <p class="small mb-0">No tienes Real Prestaciones pendientes</p>
                </div>
            </div>
        `;
        toastButton.classList.add('d-none');
    }
    
    // Mostrar el toast
    toast.show();
    
    // Cerrar automáticamente después de 5 segundos (opcional)
    setTimeout(() => {
        toast.hide();
    }, 5000);
    
    return false; // Previene la navegación inmediata
}
</script>

<style>
/* Animación personalizada para el toast */
.toast {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.toast.show {
    transform: translateY(10px);
    opacity: 1;
}

/* Asegurar que el toast esté por encima de todo */
.position-fixed {
    z-index: 1100;
}
</style>