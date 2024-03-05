<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos_fijos', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->bigInteger('servicio_id')->unsigned();
            $table->foreign('servicio_id')->references('id_servicio')->on('servicios')->onDelete('cascade');
            $table->bigInteger('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('costo_total',10,2);
            $table->string('pdf');
            $table->string('estado');
            $table->string('notas');
            $table->string('comprobante_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_fijos');
    }
};
