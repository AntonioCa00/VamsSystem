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
        Schema::create('compras', function (Blueprint $table) {
            $table->bigIncrements('id_compra');
            $table->bigInteger('solicitud_id')->unsigned();
            $table->foreign('solicitud_id')->references('id_solicitud')->on('solicitudes');
            $table->float('costo', 10, 2);
            $table->string('factura');
            $table->tinyInteger('estatus')->default(1);
            $table->bigInteger('administrador_id')->unsigned();
            $table->foreign('administrador_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
