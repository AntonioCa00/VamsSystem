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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->bigIncrements('id_orden');
            $table->bigInteger('admin_id')->unsigned();
            $table->foreign('admin_id')->references('id')->on('users');
            $table->bigInteger('cotizacion_id')->unsigned();
            $table->foreign('cotizacion_id')->references('id_cotizacion')->on('cotizaciones')->onDelete('cascade');
            $table->bigInteger('proveedor_id')->unsigned();
            $table->foreign('proveedor_id')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
            $table->float('costo_total',10,2);
            $table->enum('tipo_pago',['0','1']);
            $table->date('dia_credito')->nullable();
            $table->string('pdf');
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden__compras');
    }
};          