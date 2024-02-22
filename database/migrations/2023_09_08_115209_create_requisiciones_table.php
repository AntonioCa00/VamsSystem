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
        Schema::create('requisiciones', function (Blueprint $table) {
            $table->bigIncrements('id_requisicion');
            $table->bigInteger('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('unidad_id')->nullable();
            $table->foreign('unidad_id')->references('id_unidad')->on('unidades')->onDelete('cascade');
            $table->string('servicio_id')->nullable();
            $table->foreign('servicio_id')->references('id_servicio')->on('servicios')->onDelete('cascade');
            $table->string('pdf');
            $table->string('notas');
            $table->string('estado');           
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
