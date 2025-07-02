@extends('layouts.app')

@section('title', 'Cargar Bases')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <!-- Tarjeta de Título -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="bi bi-upload"></i> Carga de Bases de Datos
                        </h4>
                        @include('partials.herramientas-menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Carga -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                    @endif

                    <form id="cargaForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Selección de base -->
                        <div class="mb-4">
                            <label for="base" class="form-label fw-semibold">Seleccione la base a cargar:</label>
                            <select name="base" id="base" class="form-select" required>
                                <option value="" disabled selected>Elegir base...</option>
                                <option value="unca">Planta Unca</option>
                                <option value="administracion">Planta Adm Provincia</option>
                                <option value="educacion">Planta Edu Provincia</option>
                                <option value="relojes">Relojes</option>
                            </select>
                        </div>

                        <!-- Área de arrastre -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Archivo CSV:</label>
                            <div id="dropZone" class="border border-secondary rounded p-4 text-center bg-light" style="cursor: pointer;">
                                <p class="mb-2"><i class="bi bi-file-earmark-spreadsheet fs-1"></i></p>
                                <p class="mb-0">Arrastre su archivo aquí o haga clic para seleccionarlo</p>
                                <input type="file" name="csvFile" id="csvFile" accept=".csv" class="d-none" required>
                            </div>
                            <div id="fileName" class="mt-2 text-muted small"></div>
                        </div>

                        <!-- Botón de cargar -->
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up"></i> Cargar
                        </button>
                        <!-- Barra o spinner de carga -->
                    <div id="loader" class="mt-3 text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="small mt-2">Procesando archivo, por favor espere...</p>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('csvFile');
    const fileNameDisplay = document.getElementById('fileName');
    const form = document.getElementById('cargaForm');
    const baseSelect = document.getElementById('base');
    const loader = document.getElementById('loader');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Área clickeable
    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            fileNameDisplay.textContent = 'Archivo seleccionado: ' + fileInput.files[0].name;
        }
    });

    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('bg-secondary', 'text-white');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('bg-secondary', 'text-white');
    });

    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('bg-secondary', 'text-white');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileNameDisplay.textContent = 'Archivo seleccionado: ' + e.dataTransfer.files[0].name;
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const base = baseSelect.value;
        if (!base) {
            Swal.fire('Error', 'Debe seleccionar una base antes de cargar.', 'error');
            return;
        }

        const routes = {
            'unca': "{{ route('cargar.base.unca') }}",
            'administracion': "{{ route('cargar.base.administracion') }}",
            'educacion': "{{ route('cargar.base.educacion') }}",
            'relojes': "{{ route('cargar.base.relojes') }}"
        };

        const url = routes[base];
        if (!url) {
            Swal.fire('Error', 'Base inválida.', 'error');
            return;
        }

        const formData = new FormData(form);

        loader.style.display = 'block';
        submitBtn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none';
            submitBtn.disabled = false;

            if (data.success) {
                Swal.fire('Éxito', data.message, 'success');
            } else {
                let extraInfo = '';

                if (data.sample_errors && data.sample_errors.length) {
                    extraInfo = '<br><strong>Errores:</strong><ul style="text-align: left;">';
                    data.sample_errors.forEach(err => {
                        extraInfo += `<li>${err}</li>`;
                    });
                    extraInfo += '</ul>';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: (data.message || 'Ocurrió un error') + extraInfo
                });
            }
        })
        .catch(() => {
            loader.style.display = 'none';
            submitBtn.disabled = false;
            Swal.fire('Error', 'Ocurrió un error inesperado', 'error');
        });
    });
});
</script>
@endsection