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
        Schema::create('programaciones', function (Blueprint $table) {
            $table->bigIncrements('id_programacion');
            $table->date('fecha_progra');
            $table->string('unidad_id')->nullable();
            $table->foreign('unidad_id')->references('id_unidad')->on('unidades')->onDelete('cascade');
            $table->string('notas')->nullable();
            $table->bigInteger('estatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programaciones');
    }
};
