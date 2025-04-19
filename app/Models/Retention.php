<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_comercio',
        'tipo_identificacion',
        'numero_identificacion',
        'telefono',
        'retencion_porcentaje',
        'fecha_emision',
        'numero_factura',
        'numero_control',
        'nro_comprobante',
        'numero_debito',
        'numero_nota_credito',
        'tipo_transaccion',
        'numero_factura_afectada',
        'total_incluyendo_iva',
        'saldo_credito',
        'base_imponible',
        'alic',
        'impuesto_iva',
        'impuesto_retenido',
    ];
}
