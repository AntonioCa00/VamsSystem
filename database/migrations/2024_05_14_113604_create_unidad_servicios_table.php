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
        Schema::create('unidad_servicios', function (Blueprint $table) {
            $table->bigIncrements('id_unidad_serv');
            $table->string('unidad_id')->nullable();
            $table->foreign('unidad_id')->references('id_unidad')->on('unidades')->onDelete('cascade');
            $table->integer('km_mantenimiento');
            $table->string('contador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_servicios');
    }
};