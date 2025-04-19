<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetentionsTable extends Migration
{
    public function up()
    {
        Schema::create('retentions', function (Blueprint $table) {
            $table->id();
            // Datos básicos del comercio
            $table->string('nombre_comercio');
            $table->enum('tipo_identificacion', ['J', 'V']);
            $table->string('numero_identificacion', 9);
            $table->string('telefono', 20);  // Almacena el código y número concatenados, ejemplo: "4126448242"

            // Datos de la retención
            $table->date('fecha_emision');
            $table->string('numero_factura')->nullable();
            $table->string('numero_control');
            $table->string('numero_debito')->nullable();
            $table->string('numero_nota_credito')->nullable();
            $table->enum('tipo_transaccion', ['01','02','03']);
            $table->string('numero_factura_afectada')->nullable();
            
            // Campos numéricos (usar decimal con precisión y escala adecuadas)
            $table->decimal('total_incluyendo_iva', 15, 2)->nullable();
            $table->decimal('saldo_credito', 15, 2)->nullable();
            $table->decimal('base_imponible', 15, 2)->nullable();
            $table->decimal('alic', 5, 2)->nullable();  // Por ejemplo, 16.00 o 8.00
            $table->decimal('impuesto_iva', 15, 2)->nullable();
            
            // Campos de retención
            $table->enum('retencion_porcentaje', ['75','100']);
            $table->decimal('impuesto_retenido', 15, 2)->nullable();
            
            // Correlativo generado
            $table->string('nro_comprobante')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retentions');
    }
}
