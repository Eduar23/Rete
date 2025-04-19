<?php

namespace App\Http\Controllers;

use App\Models\Retention;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RetentionController extends Controller
{
    public function menu()
    {
        return view('menu');
    }

    public function create()
    {
        return view('retentions.create');
    }
    
    public function store(Request $request)
    {
    // Si los campos numéricos vienen formateados (por ejemplo: "11.619,84"), los convertimos a formato numérico estándar.
    // NOTA: Asegúrate de que en el formulario se envían como string (inputs de tipo text).
    $request->merge([
        'base_imponible'        => $this->parseFormattedNumber($request->input('base_imponible')),
        'saldo_credito'           => $this->parseFormattedNumber($request->input('saldo_credito') ?? '0'),
        'total_incluyendo_iva'    => $this->parseFormattedNumber($request->input('total_incluyendo_iva') ?? '0'),
        'impuesto_iva'            => $this->parseFormattedNumber($request->input('impuesto_iva') ?? '0'),
        'impuesto_retenido'       => $this->parseFormattedNumber($request->input('impuesto_retenido') ?? '0'),
    ]);

    // Validar datos de entrada (sin dirección fiscal)
    $validated = $request->validate([
        'nombre_comercio'         => 'required|string|max:255',
        'tipo_identificacion'     => 'required|in:J,V',
        'numero_identificacion'   => 'required|string|digits:9',
        'codigo_telefono'         => 'required|in:412,414,416,424,426',
        'telefono'                => 'required|digits:7',
        // Datos de la retención
        'fecha_emision'           => 'required|date',
        'numero_factura'          => 'required|string|max:50',
        'numero_control'          => 'required|string|max:50',
        'numero_debito'           => 'nullable|string|max:50',
        'numero_nota_credito'     => 'nullable|string|max:50',
        'tipo_transaccion'        => 'required|in:01,02,03',
        'numero_factura_afectada' => 'nullable|string|max:50',
        'total_incluyendo_iva'    => 'nullable|numeric',
        'saldo_credito'           => 'nullable|numeric',
        'base_imponible'          => 'nullable|numeric',
        'alic'                    => 'required|in:16,8',
        'impuesto_iva'            => 'nullable|numeric',
        'impuesto_retenido'       => 'nullable|numeric',
        'retencion_porcentaje'    => 'required|in:75,100',
    ]);

    // Unir el teléfono (código + número)
    $validated['telefono'] = $validated['codigo_telefono'] . $validated['telefono'];
    unset($validated['codigo_telefono']);

    // Generar el número de comprobante con formato: YYYYMM + 8 dígitos (correlativo global)
    $lastRetention = Retention::orderBy('nro_comprobante', 'desc')->first();
    if ($lastRetention) {
        $lastConsecutive = (int) substr($lastRetention->nro_comprobante, 6, 8);
        $newConsecutive  = $lastConsecutive + 1;
    } else {
        $newConsecutive  = 1;
    }
    $newConsecutiveStr = str_pad($newConsecutive, 8, '0', STR_PAD_LEFT);
    $validated['nro_comprobante'] = date('Y') . date('m') . $newConsecutiveStr;

    // Guardar en la base de datos
    $retention = Retention::create($validated);

    // Retornar la respuesta JSON con el ID para abrir el PDF en nueva pestaña
    return response()->json([
        'success' => true,
        'id'      => $retention->id,
    ]);
    }

    private function parseFormattedNumber($value)
    {
    // Elimina puntos (separador de miles) y reemplaza la coma decimal por un punto
    $cleaned = str_replace('.', '', $value);
    $cleaned = str_replace(',', '.', $cleaned);
    return floatval($cleaned);
    }

    
    public function showPdf($id)
    {
        $retention = Retention::findOrFail($id);
        
        // Utilizar "base_imponible" en lugar de "monto_base", según el nuevo formulario.
        // Calculamos el Impuesto IVA usando la alícuota seleccionada (16% o 8%)
        $iva = $retention->base_imponible * ($retention->alic / 100);
        // El Total compra incluyendo IVA se calcula sumando la base, el IVA y el valor de "saldo_credito" si lo hay.
        $total = $retention->base_imponible + $iva + ($retention->saldo_credito ?? 0);
        // Calcular el Impuesto Retenido en función del porcentaje de retención
        $impuesto_retenido = $iva * ($retention->retencion_porcentaje / 100);
        
        $datosEmpresa = [
            'nombre_agente'    => 'SERVICIOS TÉCNICOS HORIZONTE, C.A.',
            'rif_agente'       => 'J296283168',
            'direccion_agente' => 'CALLE 60A ENTRE AVS 9B Y 10 CASA # 9B-33 SECTOR PUEBLO NUEVO',
            'telefono_agente'  => '0416-1901376',
        ];
        
        $data = [
            'datosEmpresa'      => $datosEmpresa,
            'retention'         => $retention,
            'iva'               => $iva,
            'total'             => $total,
            'impuesto_retenido' => $impuesto_retenido,
        ];
        
        $pdf = Pdf::loadView('retentions.pdf_modelo', $data);
        // Papel tipo carta (letter) en orientación horizontal
        $pdf->setPaper('letter', 'landscape');
        return $pdf->stream("Comprobante_{$retention->nro_comprobante}.pdf");
    }
    
    // Método para buscar retenciones por nombre de comercio
    public function search(Request $request)
    {
        $nombre_comercio = $request->input('nombre_comercio');
        $query = Retention::query();
        if ($nombre_comercio) {
            $query->where('nombre_comercio', 'like', "%$nombre_comercio%");
        }
        $retentions = $query->orderBy('created_at', 'desc')->get();
        
        return view('retentions.search', compact('retentions'));
    }
    
    // Método para generar reportes por rango de fechas (quincenal o mensual)
    public function report(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin    = $request->input('fecha_fin');
        $tipo_reporte = $request->input('tipo_reporte'); // 'quincenal' o 'mensual'
        
        $query = Retention::query();
        if ($fecha_inicio && $fecha_fin) {
            $query->whereBetween('fecha_emision', [$fecha_inicio, $fecha_fin]);
        }
        $retentions = $query->orderBy('fecha_emision', 'asc')->get();
        
        return view('retentions.report', compact('retentions'));
    }
}
