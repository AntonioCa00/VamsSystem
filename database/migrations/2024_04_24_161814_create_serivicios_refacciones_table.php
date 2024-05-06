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
        Schema::create('servicios_refacciones', function (Blueprint $table) {
            $table->bigIncrements('id_serivicio_ref');
            $table->bigInteger('mantenimiento_id')->unsigned();
            $table->foreign('mantenimiento_id')->references('id_mantenimiento')->on('mantenimientos')->onDelete('cascade');
            $table->bigInteger('refaccion_id')->unsigned();
            $table->foreign('refaccion_id')->references('id_refaccion_mant')->on('ref_mantenimientos')->onDelete('cascade');
            $table->integer('tiempo_cambio');
            $table->string('cambio_limpieza');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_refacciones');
    }
};
