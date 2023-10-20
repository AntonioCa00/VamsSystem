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
        Schema::create('almacen', function (Blueprint $table) {
            $table->bigIncrements('id_refaccion');
            $table->string('nombre');
            $table->string('marca');
            $table->string('anio'); 
            $table->string('modelo');           
            $table->string('descripcion');
            $table->integer('stock');
            $table->bigInteger('entrada_id')->unsigned()->nullable();
            $table->foreign('entrada_id')->references('id_entrada')->on('entradas');
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
