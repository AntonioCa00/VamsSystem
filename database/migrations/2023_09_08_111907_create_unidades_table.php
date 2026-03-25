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
    Schema::create('unidades', function (Blueprint $table) {
        $table->id(); // PK autoincremental (INT)
        $table->string('id')->unique(); // identificador de negocio
        $table->string('Numero_ec')->nullable();
        $table->string('tipo');
        $table->string('estado');
        $table->string('anio_unidad');
        $table->string('marca');
        $table->string('modelo');
        $table->string('caracteristicas');
        $table->string('n_de_serie');
        $table->string('n_de_permiso');
        $table->integer('kilometraje')->nullable();
        $table->tinyInteger('estatus')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
