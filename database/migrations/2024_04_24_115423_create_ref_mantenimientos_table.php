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
        Schema::create('ref_mantenimientos', function (Blueprint $table) {
            $table->bigIncrements('id_refaccion_mant');
            $table->string('nombre');
            $table->integer('cantidad');
            $table->string('unidad_medida');
            $table->integer('tiempo_cambio');
            $table->bigInteger('tipo_mant')->unsigned();
            $table->foreign('tipo_mant')->references('id_mantenimiento')->on('mantenimientos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_mantenimientos');
    }
};
