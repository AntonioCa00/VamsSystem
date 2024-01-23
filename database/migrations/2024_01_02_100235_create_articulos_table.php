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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('requisicion_id')->unsigned();
            $table->foreign('requisicion_id')->references('id_requisicion')->on('requisiciones')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('unidad');
            $table->string('descripcion');
            $table->float('precio_unitario',10,2)->nullable();
            $table->string('unidad_id')->nullable();
            $table->foreign('unidad_id')->references('id_unidad')->on('unidades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
