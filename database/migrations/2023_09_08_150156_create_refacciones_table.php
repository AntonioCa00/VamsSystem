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
        Schema::create('refacciones', function (Blueprint $table) {
            $table->bigIncrements('id_refaccion');
            $table->string('nombre');
            $table->string('modelo');
            $table->string('anio');
            $table->string('marca');
            $table->string('descripcion');
            $table->integer('stock');
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refacciones');
    }
};
