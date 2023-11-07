<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{

    protected $fillable = [
        'id_unidad',
        'tipo',
        'estado',
        'anio_unidad',
        'marca',
        'kilometraje',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
