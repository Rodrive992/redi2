{{-- Ejemplo: alta_vida.blade.php --}}
<div>
    <h4><strong>ALTA SEGURO DE VIDA PERSONAL DEL ESTADO LEY N° 13.003 (CODIGO 321)</strong></h4>
    <br>
    <ul style="list-style-type: none; padding-left: 0;">
        <li>Para comenzar con el proceso de ALTAS de los seguros de vida, se debe ingresar al sistema SIU MAPUCHE, seleccionar el agente y elegir la opción "EDITAR" para ingresar a su legajo.</li>
        <br>
        <img src="{{ asset('complementos/assest/img/proced_seguros1.jpg') }}" alt="Imagen Proceso Alta 1" class="img-responsive">
        <li>Realizado este paso, se debe seleccionar la opción "NOVEDADES", en este bloque se encuentran todos los códigos de descuentos.</li>
        <img src="{{ asset('complementos/assest/img/proced_seguros2.jpg') }}" alt="Imagen Proceso Alta 2" class="img-responsive">
        <li>Para comenzar con el proceso de alta debemos tener en cuenta que en el casillero de <strong>BUSQUEDA AVANZADA</strong> se encuentren las siguientes opciones activadas:</li>
        <ul>
            <li>ESTADO (*): Vigente - Histórica. El tilde debe estar presente en "VIGENTE".</li>
            <li>TIPO DE CONCEPTO (*): Permanente - Liquidación. El tilde debe estar presente en "PERMANENTE".</li>
            <li>TIPO DE NOVEDAD (*): Legajo - Cargo. El tilde debe estar presente en "LEGAJO".</li>
        </ul>
        <li>Para agregar el descuento del seguro de vida obligatorio personal del estado se debe seleccionar "Agregar Novedad Permanente de Legajo".</li>
        <img src="{{ asset('complementos/assest/img/proced_seguros3.jpg') }}" alt="Imagen Proceso Alta 3" class="img-responsive">
        <li>Cuando ingresamos en este bloque, tenemos una serie de pasos a seguir para realizar el alta del seguro:</li>
        <ol>
            <li><strong>CONCEPTO- NÚMERO:</strong> se debe colocar el número de código del seguro a activar, en este caso es el N° 321.</li>
            <li><strong>CLASE:</strong> COMUN-SEGUROS. Se debe tildar SEGUROS.</li>
            <li><strong>COMIENZO:</strong> se debe colocar el mes y el año en que se da de alta el agente.</li>
            <li><strong>COMPORTAMIENTO:</strong> Normal-Forzado-Anulado-No Acumulable. Se debe tildar "Normal".</li>
            <li><strong>CANTIDAD:</strong> se debe colocar el número 0.</li>
            <li><strong>AGREGAR:</strong> debajo de la pantalla, en el lado derecho, se encuentra el botón <strong>AGREGAR</strong>. Al presionarlo, el proceso de alta queda realizado.</li>
        </ol>
        <br>
        <img src="{{ asset('complementos/assest/img/proced_seguros4.jpg') }}" alt="Imagen Proceso Alta 4" class="img-responsive">
    </ul>
</div>
