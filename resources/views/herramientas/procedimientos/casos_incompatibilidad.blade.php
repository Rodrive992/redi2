<div class="container px-3 py-4 bg-white border rounded shadow-sm">
    <h4 class="text-primary fw-bold mb-3">
        <i class="bi bi-exclamation-diamond-fill me-2"></i>
        Casos de Incompatibilidad
    </h4>

    <p class="mb-4">
        <strong>Descargue los modelos de nota correspondientes a cada situación.</strong>
    </p>

    <h5 class="fw-bold mb-3">CONTROL DE COMPATIBILIDAD</h5>

    <ol class="mb-4">
        <li class="mb-3">
            <strong>Verificar la declaración jurada:</strong> presentada por el agente y realizar el cruce de información en el Sistema REDI, el cual mostrará las horas trabajadas tanto en la institución como en otras.
        </li>

        <li class="mb-3">
            Si el Sistema REDI indica que el agente supera las <strong>50 horas</strong>, revisar en el legajo desde cuándo presenta esta situación.
        </li>

        <li class="mb-3">
            Si la situación de exceso de horas existe desde antes de <strong>2016</strong>, clasificar al agente como <strong>“Incompatible histórico”</strong>. Esta categoría implica que no puede tomar más horas adicionales.
        </li>

        <li class="mb-3">
            Si el agente <strong>declaró</strong> el exceso de horas, se trata de un caso de <strong>“Incompatibilidad”</strong>. Elaborar una nota donde el agente se comprometa a cumplir con lo establecido por el convenio colectivo.
            <br>
            <a href="{{ asset('documentos/NOTA_INCOMPATIBILIDAD.docx') }}" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Descargar Nota de Incompatibilidad
            </a>
        </li>

        <li class="mb-3">
            Si el agente <strong>no declaró</strong> el exceso de horas y está en situación de incompatibilidad, se trata de <strong>“Incompatibilidad y Omisión”</strong>. Elaborar una nota que el agente debe firmar, comprometiéndose a rectificar o ratificar la información registrada.
            <br>
            <a href="{{ asset('documentos/NOTA_INCOMPATIBILIDAD_OMISION.docx') }}" class="btn btn-outline-danger btn-sm mt-2" target="_blank">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Descargar Nota de Incompatibilidad y Omisión
            </a>
        </li>

        <li class="mb-3">
            Si el agente omitió declarar horas pero estas no generan incompatibilidad, o si no se puede verificar cuántas posee, clasificar como <strong>“Omisión de cargo”</strong>. Notificar al agente sobre la omisión y solicitar que rectifique o ratifique la información.
            <br>
            <a href="{{ asset('documentos/NOTA_OMISION.docx') }}" class="btn btn-outline-warning btn-sm mt-2" target="_blank">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Descargar Nota de Omisión
            </a>
        </li>

        <li class="mb-3">
            Una vez firmada la nota, archivarla en la carpeta correspondiente a incompatibilidades y registrar el caso en el cuadro de seguimiento.
        </li>

        <li class="mb-3">
            Realizar seguimiento periódico de las rectificaciones pendientes a través del cruce en el Sistema REDI.
        </li>
    </ol>
</div>