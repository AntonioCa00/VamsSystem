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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->bigIncrements('id_solicitud');
            $table->bigInteger('encargado_id')->unsigned();
            $table->foreign('encargado_id')->references('id')->on('users');
            $table->string('estado');
            $table->string('unidad_id');
            $table->foreign('unidad_id')->references('id_unidad')->on('unidades');
            $table->string('descripcion');
            $table->bigInteger('refaccion_id')->unsigned();
            $table->foreign('refaccion_id')->references('id_refaccion')->on('refacciones');
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
