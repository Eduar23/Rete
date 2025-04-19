@extends('layouts.app')

@section('content')

    <!-- Menú de navegación responsive -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('menu') }}">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('retention.create') }}">Generar Retención</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('retention.search') }}">Buscar Retenciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('retention.report') }}">Reportes</a>
                </li>
            </ul>

            @auth
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

<div class="container">
    <h2>Generar Comprobante de Retención</h2>
    <form id="retentionForm">
        @csrf

        {{-- SECCIÓN: DATOS BÁSICOS --}}
        <div class="form-group">
            <label>Nombre del Comercio</label>
            <input type="text" class="form-control" name="nombre_comercio" required>
        </div>

        <div class="form-group row">
            <label for="tipo_identificacion" class="col-sm-2 col-form-label">Tipo de Identificación</label>
            <div class="col-sm-2">
                <select id="tipo_identificacion" name="tipo_identificacion" class="form-control" required>
                    <option value="J">J (RIF)</option>
                    <option value="V">V (Cédula)</option>
                </select>
            </div>
            <div class="col-sm-8">
                <!-- RIF/Cédula: 9 dígitos numéricos -->
                <input type="text" name="numero_identificacion" id="numero_identificacion"
                       class="form-control" placeholder="296283168" maxlength="9"
                       pattern="\d{9}" required
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);">
                <small class="form-text text-muted">Ingrese 9 dígitos.</small>
            </div>
        </div>

        <div class="form-group row">
            <label for="telefono" class="col-sm-2 col-form-label">Número de Teléfono</label>
            <div class="col-sm-2">
                <!-- Código de teléfono -->
                <select id="codigo_telefono" name="codigo_telefono" class="form-control" required>
                    <option value="412">412</option>
                    <option value="414">414</option>
                    <option value="416">416</option>
                    <option value="424">424</option>
                    <option value="426">426</option>
                </select>
            </div>
            <div class="col-sm-8">
                <!-- 7 dígitos del número -->
                <input type="text" name="telefono" id="telefono" class="form-control"
                       placeholder="1901376" maxlength="7" pattern="\d{7}" required
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 7);">
                <small class="form-text text-muted">Ingrese 7 dígitos.</small>
            </div>
        </div>

        {{-- SECCIÓN: DATOS DE LA RETENCIÓN --}}
        <hr>
        <h4>Datos de la Retención</h4>
        <!-- Fila 1 -->
        <div class="form-group row">
            <div class="col-sm-4">
                <label>N° Fecha de Doct.</label>
                <input type="date" name="fecha_emision" class="form-control" required>
            </div>
            <div class="col-sm-4">
                <label>N° de Factura</label>
                <input type="text" name="numero_factura" class="form-control" placeholder="003235" required>
            </div>
            <div class="col-sm-4">
                <label>N° de Control</label>
                <input type="text" name="numero_control" class="form-control" placeholder="Z6C3000263" required>
            </div>
        </div>

        <!-- Fila 2: Nuevo campo N° de Débito, luego Nota de Crédito -->
        <div class="form-group row">
            <div class="col-sm-3">
                <label>N° de Débito</label>
                <input type="text" name="numero_debito" class="form-control" placeholder="---">
            </div>
            <div class="col-sm-3">
                <label>N° Nota de Crédito</label>
                <input type="text" name="numero_nota_credito" class="form-control" placeholder="---">
            </div>
            <div class="col-sm-3">
                <label>Tipo de Transacción</label>
                <select name="tipo_transaccion" class="form-control">
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label>N° Factura Afectada</label>
                <input type="text" name="numero_factura_afectada" class="form-control" placeholder="---">
            </div>
        </div>

        <!-- Fila 3: Campos numéricos -->
        <div class="form-group row">
            <div class="col-sm-4">
                <label>Total compra incluyendo IVA</label>
                <input type="text" name="total_incluyendo_iva" id="total_incluyendo_iva"
                       class="form-control" placeholder="11.619,84" readonly>
            </div>
            <div class="col-sm-4">
                <label>Sin derecho a Crédito</label>
                <!-- Se usa text para poder mostrar el formato español -->
                <input type="text" name="saldo_credito" id="saldo_credito"
                       class="form-control" placeholder="0,00">
            </div>
            <div class="col-sm-4">
                <label>Base Imponible</label>
                <!-- Cambiamos el type a text para ingresar con formato -->
                <input type="text" name="base_imponible" id="base_imponible"
                       class="form-control" placeholder="11.619,84" required>
            </div>
        </div>

        <!-- Fila 4: Cálculos en vivo -->
        <div class="form-group row">
            <div class="col-sm-3">
                <label>Alicuota (%)</label>
                <select name="alic" id="alic" class="form-control" required>
                    <option value="16">16%</option>
                    <option value="8">8%</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label>Impuesto IVA</label>
                <input type="text" name="impuesto_iva" id="impuesto_iva"
                       class="form-control" placeholder="1.858,00" readonly>
            </div>
            <div class="col-sm-3">
                <label>Tipo de Retención</label>
                <select name="retencion_porcentaje" id="retencion_porcentaje" class="form-control" required>
                    <option value="75">75%</option>
                    <option value="100">100%</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label>Impuesto Retenido</label>
                <input type="text" name="impuesto_retenido" id="impuesto_retenido"
                       class="form-control" placeholder="1.393,50" readonly>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar y Generar PDF</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Convierte un string con formato español (puntos para miles, coma para decimales) a número
function parseFormattedNumber(value) {
    // Quita los puntos y reemplaza la coma decimal por punto
    return parseFloat(value.replace(/\./g, '').replace(',', '.'));
}

// Formatea un número a formato "es-ES" con separador de miles (.) y coma decimal
function formatNumberCustom(n) {
    var parts = n.toFixed(2).split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(",");
}

// Función para formatear el valor de un campo de entrada al salir (blur)
function formatNumberInput(element) {
    var value = $(element).val();
    if(value) {
        var number = parseFormattedNumber(value);
        if(!isNaN(number)) {
            $(element).val(formatNumberCustom(number));
        }
    }
}

// Función para recalcular los totales en vivo
function recalculateTotals() {
    var base = parseFormattedNumber($('#base_imponible').val()) || 0;
    var credito = parseFormattedNumber($('#saldo_credito').val()) || 0;
    var alic = parseFloat($('#alic').val()) || 0;
    var retencion = parseFloat($('#retencion_porcentaje').val()) || 0;

    var impuestoIVA = base * (alic / 100);
    var totalCompra = base + impuestoIVA + credito;
    var impuestoRetenido = impuestoIVA * (retencion / 100);

    $('#impuesto_iva').val( formatNumberCustom(impuestoIVA) );
    $('#total_incluyendo_iva').val( formatNumberCustom(totalCompra) );
    $('#impuesto_retenido').val( formatNumberCustom(impuestoRetenido) );
}

// Asignar eventos para el formateo al salir de los inputs numéricos
$('#base_imponible').on('blur', function(){
    formatNumberInput(this);
});
$('#saldo_credito').on('blur', function(){
    formatNumberInput(this);
});

// Vincular eventos para que se recalculen los totales en vivo
$('#base_imponible, #saldo_credito, #alic, #retencion_porcentaje').on('input change', function(){
    recalculateTotals();
});

// Inicializar los cálculos (por si ya hay algún valor pre-cargado)
recalculateTotals();

// Manejar el envío del formulario
$('#retentionForm').on('submit', function(e){
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        url: "{{ route('retention.store') }}",
        method: "POST",
        data: formData,
        success: function(response){
            if(response.success){
                window.open("{{ url('retention/pdf') }}/" + response.id, '_blank');
            }
        },
        error: function(xhr){
            console.log("Estado:", xhr.status);
            console.log("Respuesta:", xhr.responseText);
            alert("Error al procesar la solicitud");
        }
    });
});
</script>
@endsection
