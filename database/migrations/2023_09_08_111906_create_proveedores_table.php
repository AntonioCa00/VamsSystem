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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('id_proveedor');
            $table->string('nombre');
            $table->string('telefono');
            $table->string('telefono2');
            $table->string('contacto');
            $table->string('direccion');
            $table->string('domicilio');
            $table->string('rfc');
            $table->string('correo');
            $table->string('CIF');
            $table->string('banco')->nullable();
            $table->string('n_cuenta')->nullable();
            $table->string('n_cuenra_clabe')->nullable();
            $table->string('estado_cuenta')->nullable();
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
