<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Retención</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-left {
            /* Aquí se pueden colocar logotipos u otros datos si se requieren */
        }
        .header-right {
            text-align: right;
            font-size: 12px;
        }
        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .subtitulo {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px;
        }
        .encabezado {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .no-border {
            border: none;
        }
    </style>
</head>
<body>
    <div class="titulo">COMPROBANTE DE RETENCIÓN DE IMPUESTO AL VALOR AGREGADO</div>
    <div class="subtitulo">
        Ley de I.V.A. Art. 11: "La Administración Tributaria podrá designar como responsables del impuesto,
        con carácter de agentes de retención, a quienes por sus funciones públicas o por razón de sus
        actividades privadas intervengan en operaciones gravadas con el impuesto establecido en esta
        Ley."
    </div>
    
    {{-- DATOS DEL AGENTE DE RETENCIÓN --}}
    <table>
        <tr>
            <td class="encabezado" colspan="4">DATOS DEL AGENTE DE RETENCIÓN</td>
        </tr>
        <tr>
            <td>Nombre o Razón Social:</td>
            <td>{{ $datosEmpresa['nombre_agente'] }}</td>
            <td>Nro. de Comprobante:</td>
            <td>{{ $retention->nro_comprobante }}</td>
        </tr>
        <tr>
            <td>RIF:</td>
            <td>{{ $datosEmpresa['rif_agente'] }}</td>
            <td>Fecha de Emisión:</td>
            <td>{{ date('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Dirección:</td>
            <td>{{ $datosEmpresa['direccion_agente'] }}</td>
            <td>Fecha de Entrega:</td>
            <td>{{ date('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Teléfono:</td>
            <td>{{ $datosEmpresa['telefono_agente'] }}</td>
            <td>Periodo Fiscal:</td>
            <td>{{ date('Y/m') }}</td>
        </tr>
    </table>

    {{-- DATOS DEL BENEFICIARIO --}}
    <table>
        <tr>
            <td class="encabezado" colspan="2">DATOS DEL PROVEEDOR</td>
        </tr>
        <tr>
            <td>Nombre o Razón Social:</td>
            <td>{{ $retention->nombre_comercio }}</td>
        </tr>
        <tr>
            <td>RIF / Cédula:</td>
            <td>{{ $retention->tipo_identificacion }}{{ $retention->numero_identificacion }}</td>
        </tr>
        <tr>
            <td>Teléfono:</td>
            <td>{{ $retention->telefono }}</td>
        </tr>
    </table>

    {{-- DATOS DE LA RETENCIÓN --}}
    <table>
    <tr>
        <td class="encabezado" colspan="12">DATOS DE LA RETENCIÓN</td>
    </tr>
    <tr class="encabezado center">
        <td>N° Fecha de doct.</td>
        <td>N° Factura</td>
        <td>N° Control</td>
        <td>N° Débito</td>
        <td>N° Nota Crédito</td>
        <td>Tipo Trans.</td>
        <td>N° Fact. Afect.</td>
        <td>Total c/IVA</td>
        <td>Sin Der. Crédito</td>
        <td>Base Imponible</td>
        <td>(%) Alic.</td>
        <td>Impuesto IVA</td>
        <td>% Retención</td>
        <td>IVA Retenido</td>
    </tr>
    <tr class="center">
        <td>{{ $retention->fecha_emision }}</td>
        <td>{{ $retention->numero_factura }}</td>
        <td>{{ $retention->numero_control }}</td>
        <td>{{ $retention->numero_debito ?: '---' }}</td>
        <td>{{ $retention->numero_nota_credito ?: '---' }}</td>
        <td>{{ $retention->tipo_transaccion ?? '---' }}</td>
        <td>{{ $retention->numero_factura_afectada ?: '---' }}</td>

        {{-- Total compra incluyendo IVA --}}
        <td class="right">
            {{ number_format($retention->total_incluyendo_iva ?? 0, 2, ',', '.') }}
        </td>
        
        {{-- Sin Derecho a Crédito (saldo_credito) --}}
        <td class="right">
            {{ number_format($retention->saldo_credito ?? 0, 2, ',', '.') }}
        </td>
        
        {{-- Base Imponible --}}
        <td class="right">
            {{ number_format($retention->base_imponible ?? 0, 2, ',', '.') }}
        </td>
        
        {{-- Alicuota (%) --}}
        <td>
            {{ $retention->alic }}%
        </td>
        
        {{-- IVA (calculado o proveniente del controlador) --}}
        <td class="right">
            {{ number_format($iva ?? 0, 2, ',', '.') }}
        </td>

        {{-- Tipo de Retención --}}
        <td>
            {{ $retention->retencion_porcentaje }}%
        </td>
        
        {{-- IVA Retenido (calculado o proveniente del controlador) --}}
        <td class="right">
            {{ number_format($impuesto_retenido ?? 0, 2, ',', '.') }}
        </td>
    </tr>
    </table>


    <br>
    <p style="font-size:10px;">
        <strong>Nota:</strong> El presente comprobante se emite en atención a lo establecido en el artículo 16 de la Providencia
        Administrativa N° SNAT/2015/0049 de fecha 14/05/2015.
    </p>

    <!-- Sección de firmas al final -->
    <div style="margin-top: 50px;">
        <table width="100%" style="border: none;">
            <tr style="border: none;">
                <td style="width: 50%; border: none; text-align: center;">
                    <!-- Imagen de la firma o sello del Agente de Retención -->
                    <img src="{{ public_path('images/firma_agente.png') }}" alt="Firma" style="width: 120px;"><br>
                    <hr style="width: 60%;">
                    <p>Agente de Retención</p>
                </td>
                <td style="width: 50%; border: none; text-align: center;">
                    <!-- Espacio para la firma de "Recibe Conforme" -->
                    <br><br>
                    <hr style="width: 60%;">
                    <p>Recibe Conforme</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>