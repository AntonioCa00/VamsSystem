<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{

    protected $fillable = [
        'id',
        'id',
        'tipo',
        'Numero_ec',
        'estado',
        'anio_unidad',
        'marca',
        'modelo',
        'caracteristicas',
        'n_de_serie',
        'n_de_permiso',
        'estatus',
        'kilometraje',
        'created_at',
        'updated_at',        
    ];

    use HasFactory;
}
