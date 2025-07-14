@extends('layouts.app')

@section('title', 'REDI 2.0 - Real Prestación')

@section('content')
<div class="container-fluid px-0">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-primary mb-0">
                                <i class="bi bi-journal-text me-2"></i> Real Prestación - UNCA
                            </h3>
                            @include('partials.herramientas-menu-externo')
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Botón para descargar plantilla -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('real_prestacion_externo.descargar_plantilla') }}" class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i> Descargar Plantilla
                                </a>
                            </div>
                        </div>

                        <!-- Formulario para subir archivo -->
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="bi bi-upload me-2"></i> Subir Real Prestación
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('real_prestacion_externo.subir_archivo') }}" method="POST" enctype="multipart/form-data" id="form-real-prestacion">
                                            @csrf
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="mes" class="form-label">Mes</label>
                                                    <select class="form-select" id="mes" name="mes" required>
                                                        <option value="" selected disabled>Seleccionar mes</option>
                                                        <option value="01">Enero</option>
                                                        <option value="02">Febrero</option>
                                                        <option value="03">Marzo</option>
                                                        <option value="04">Abril</option>
                                                        <option value="05">Mayo</option>
                                                        <option value="06">Junio</option>
                                                        <option value="07">Julio</option>
                                                        <option value="08">Agosto</option>
                                                        <option value="09">Septiembre</option>
                                                        <option value="10">Octubre</option>
                                                        <option value="11">Noviembre</option>
                                                        <option value="12">Diciembre</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ano" class="form-label">Año</label>
                                                    <select class="form-select" id="ano" name="ano" required>
                                                        <option value="" selected disabled>Seleccionar año</option>
                                                        @for($i = date('Y'); $i >= date('Y') -1; $i--)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="archivo_real_prestacion" class="form-label">Archivo Excel</label>
                                                <div class="dropzone" id="dropzone-real-prestacion">
                                                    <div class="dz-message">
                                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                                        <p class="my-2">Arrastra y suelta el archivo aquí o haz clic para seleccionar</p>
                                                        <p class="small text-muted">Solo se aceptan archivos Excel (.xlsx, .xls)</p>
                                                    </div>
                                                    <input type="file" class="form-control d-none" id="archivo_real_prestacion" name="archivo_real_prestacion" accept=".xlsx,.xls" required>
                                                </div>
                                                <div id="file-name" class="mt-2 small text-muted"></div>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary" id="btn-submit">
                                                    <i class="bi bi-send me-2"></i> Enviar Real Prestación
                                                </button>
                                            </div>
                                        </form>
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

@push('styles')
<style>
    .dropzone {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .dropzone:hover {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .dz-message {
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar el dropzone personalizado
        const dropzone = document.getElementById('dropzone-real-prestacion');
        const fileInput = document.getElementById('archivo_real_prestacion');
        const fileNameDisplay = document.getElementById('file-name');
        
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
        
        // Manejar drag and drop
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-primary');
            this.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
        });
        
        ['dragleave', 'dragend'].forEach(type => {
            dropzone.addEventListener(type, function() {
                this.classList.remove('border-primary');
                this.style.backgroundColor = '';
            });
        });
        
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary');
            this.style.backgroundColor = '';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileNameDisplay.textContent = e.dataTransfer.files[0].name;
            }
        });
        
        // Validar el formulario antes de enviar
        document.getElementById('form-real-prestacion').addEventListener('submit', function(e) {
            const mes = document.getElementById('mes').value;
            const ano = document.getElementById('ano').value;
            const archivo = document.getElementById('archivo_real_prestacion').files[0];
            
            if (!mes || !ano || !archivo) {
                e.preventDefault();
                alert('Por favor complete todos los campos y seleccione un archivo.');
                return false;
            }
            
            // Mostrar spinner en el botón de enviar
            const submitBtn = document.getElementById('btn-submit');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Enviando...';
            submitBtn.disabled = true;
        });
    });
</script>
@endpush