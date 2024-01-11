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
        Schema::create('salidas', function (Blueprint $table) {
            $table->bigIncrements('id_salida');
            $table->bigInteger('requisicion_id')->unsigned();
            $table->foreign('requisicion_id')->references('id_requisicion')->on('requisiciones')->onDelete('cascade');
            $table->integer('cantidad');            
            $table->bigInteger('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('refaccion_id');
            $table->foreign('refaccion_id')->references('clave')->on('almacen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
