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
        Schema::create('historial_mants', function (Blueprint $table) {
            $table->bigIncrements('id_historial');
            $table->bigInteger('programacion_id')->unsigned()->nullable();
            $table->foreign('programacion_id')->references('id_programacion')->on('programaciones')->onDelete('cascade');
            $table->bigInteger('mantenimiento_id')->unsigned();
            $table->foreign('mantenimiento_id')->references('id_mantenimiento')->on('mantenimientos')->onDelete('cascade');
            $table->integer('estatus');
            $table->integer('km_final');
            $table->integer('ciclo');
            $table->string('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_mants');
    }
};