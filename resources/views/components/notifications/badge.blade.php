@php
$count = DB::table('mesa')
    ->where('entregado_a', auth()->user()->name)
    ->where('estado', 'Pendiente')
    ->count();
@endphp

<div class="position-relative me-3">
    <button class="btn btn-link text-white p-0" 
            data-bs-toggle="offcanvas" 
            data-bs-target="#notificationsSidebar">
        <i class="bi bi-envelope-fill fs-4"></i>
        @if($count > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $count }}
        </span>
        @endif
    </button>
</div>