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
            $table->string('clave')->primary();
            $table->string('ubicacion');
            $table->string('descripcion');
            $table->string('medida'); 
            $table->string('marca');           
            $table->string('cantidad');
            $table->bigInteger('entrada_id')->unsigned()->nullable();
            $table->foreign('entrada_id')->references('id_entrada')->on('entradas')->onDelete('cascade');
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
