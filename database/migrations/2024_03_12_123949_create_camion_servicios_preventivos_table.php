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
        Schema::create('camion_servicios_preventivos', function (Blueprint $table) {
            $table->bigIncrements('id_servicio_preventivo');
            $table->string('unidad_id')->nullable();
            $table->foreign('unidad_id')->references('id_unidad')->on('unidades')->onDelete('cascade');
            $table->integer('filtro_aire_grande');
            $table->integer('filtro_aire_chico');
            $table->integer('filtro_diesel');
            $table->integer('filtro_aceite');
            $table->integer('wk1016_trampa');
            $table->integer('aceite_motor');
            $table->integer('filtro_urea');
            $table->integer('anticongelante');
            $table->integer('aceite_direccion');
            $table->integer('banda_poles');
            $table->integer('ajuste_frenos');
            $table->integer('engrasado_chasis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camion_servicios_preventivos');
    }
};
