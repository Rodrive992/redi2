<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Incluir CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Incluir JS de Select2 y jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    
    <!-- Incluir JS de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <title>@yield('title') - REDI 2.0</title>

    <style>
        .offcanvas { z-index: 1060; }
        .navbar { z-index: 1030; }

                /* Asegura que SweetAlert2 esté por encima */
        .swal2-popup {
            z-index: 9999 !important;
        }

        /* Asegura que el Select2 tenga un z-index alto */
        .select2-container--open {
            z-index: 9999 !important;
        }

        /* Opcional: Si el dropdown sigue teniendo problemas, ajusta el z-index globalmente para el offcanvas */
        .offcanvas {
            z-index: 1050 !important; /* Ajusta si es necesario */
        }
    </style>

    @stack('styles')
</head>
<body>
    @unless(Request::is('login'))
        @include('partials.navbar')
    @endunless

    <main class="container-fluid py-4">
        @yield('content')
    </main>

    @unless(Request::is('login'))
        @include('partials.sidebar-notifications')
    @endunless

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>