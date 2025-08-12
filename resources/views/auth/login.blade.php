@extends('layouts.app')

@section('title', 'Login - REDI 2.0')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 rounded-4" style="width: 100%; max-width: 420px;">
        <div class="card-header bg-primary text-white text-center rounded-top-4 py-3">
            <h3 class="mb-0 fw-bold">REDI 2.0</h3>
            <small class="text-white-50">Sistema de Gestión de Personal</small>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- CUIL -->
                <div class="mb-3">
                    <label for="cuil" class="form-label fw-semibold">CUIL</label>
                    <input id="cuil" type="text" 
                           class="form-control @error('cuil') is-invalid @enderror" 
                           name="cuil" value="{{ old('cuil') }}" required autofocus placeholder="Ej: 20XXXXXXXXX">
                    @error('cuil')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input id="password" type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" required placeholder="Ingrese su contraseña">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Recordarme -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>

                <!-- Botón -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted small py-2">
            &copy; {{ date('Y') }} Universidad Nacional de Catamarca - Dirección General de Personal
        </div>
    </div>
</div>
@endsection
