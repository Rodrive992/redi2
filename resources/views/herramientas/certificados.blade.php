@extends('layouts.app')

@section('title', 'Certificados')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Tarjeta de Título -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-file-earmark-text"></i> Generación de Certificados
                        </h4>
                        @include('partials.herramientas-menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('herramientas.certificados') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-file-earmark-medical"></i>
                        </span>
                        <select name="tipo_certificado" class="form-select" required>
                            <option value="" disabled selected>Seleccionar tipo de certificado</option>
                            <option value="horarios" {{ request('tipo_certificado') == 'horarios' ? 'selected' : '' }}>Horarios</option>
                            <option value="cargos" {{ request('tipo_certificado') == 'cargos' ? 'selected' : '' }}>Cargos</option>
                            <option value="sueldos" {{ request('tipo_certificado') == 'sueldos' ? 'selected' : '' }}>Sueldos</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        <input type="text" class="form-control" name="dni" 
                               placeholder="Ingresar DNI..." value="{{ $dni ?? '' }}" 
                               required pattern="[0-9]+" title="Solo números">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    @if(isset($empleado) && $empleado)
    <div class="card shadow-lg mb-5">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $empleado->nombre }}</h4>
                <span class="badge bg-light text-dark fs-6">DNI: {{ $empleado->dni }}</span>
            </div>
        </div>
        <div class="card-body">
            <!-- Formulario de certificado -->
            <form id="form-certificado" method="POST" action="{{ route('herramientas.exportar_certificados') }}">
                @csrf
                <input type="hidden" name="tipo_certificado" value="{{ $tipo_certificado }}">
                <input type="hidden" name="dni" value="{{ $empleado->dni }}">

                <div class="mb-4">
                    <h5 class="text-center mb-4">DIRECCIÓN GENERAL DE PERSONAL</h5>
                    <p class="text-justify mb-4">
                        Quién suscribe, Directora General de Personal de la Universidad Nacional de Catamarca, 
                        certifica que el agente <input type="text" class="form-control d-inline" style="width: 220px;" name="nombre" value="{{ $empleado->nombre}}" required>- DNI N° <strong>{{ $empleado->dni }}</strong> 
                        reviste la siguiente situación en esta institución al día: <input type="text" class="form-control d-inline" style="width: 120px;" name="fecha_actual" value="{{ date('d-m-Y') }}" required>
                    </p>

                    <table class="table table-bordered mb-4">
                        <thead class="table-light">
                            <tr>
                                <th>Cargo</th>
                                <th>Dependencia</th>
                                <th>Carácter</th>
                                <th>Dedicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control" name="cargo" value="{{ $empleado->cargo }}" placeholder="Cargo" required></td>
                                <td><input type="text" class="form-control" name="dependencia" value="{{ $empleado->dependencia_comp }}" placeholder="Dependencia" required></td>
                                <td><input type="text" class="form-control" name="caracter" value="{{ $empleado->caracter }}" placeholder="Carácter" required></td>
                                <td><input type="text" class="form-control" name="dedicacion" value="{{ $empleado->dedicacion }}" placeholder="Dedicación" required></td>
                            </tr>
                        </tbody>
                    </table>

                    @if($tipo_certificado == 'horarios')
                    <div class="mb-4">
                        <h6>HORARIOS:</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miércoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="horario_lunes" placeholder="Ej: 08:00-12:00"></td>
                                    <td><input type="text" class="form-control" name="horario_martes" placeholder="Ej: 08:00-12:00"></td>
                                    <td><input type="text" class="form-control" name="horario_miercoles" placeholder="Ej: 08:00-12:00"></td>
                                    <td><input type="text" class="form-control" name="horario_jueves" placeholder="Ej: 08:00-12:00"></td>
                                    <td><input type="text" class="form-control" name="horario_viernes" placeholder="Ej: 08:00-12:00"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">ANTIGÜEDAD:</label>
                        <input type="text" class="form-control" name="antiguedad" placeholder="Ej: 5 años, 3 meses">
                    </div>

                    @if($tipo_certificado == 'sueldos')
                    <div class="mb-3">
                        <label class="form-label">SUELDO BRUTO:</label>
                        <input type="text" class="form-control" name="sueldo_bruto" placeholder="Ingrese sueldo bruto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SUELDO NETO:</label>
                        <input type="text" class="form-control" name="sueldo_neto" placeholder="Ingrese sueldo neto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">OBSERVACIONES:</label>
                        <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones adicionales"></textarea>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">ENTIDAD DESTINATARIA:</label>
                        <input type="text" class="form-control" name="entidad_destinataria" placeholder="Nombre de la entidad destinataria" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-file-earmark-word"></i> Exportar Certificado
                    </button>
                </div>
            </form>
        </div>
    </div>
    @elseif(request()->has('dni'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> No se encontraron resultados para el DNI: {{ request('dni') }}
    </div>
    @endif
</div>
@endsection