<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{

    protected $table = 'almacen';

    protected $fillable = [       
        'id_refaccion',
        'nombre',
        'modelo',
        'anio',
        'marca',
        'motor',
        'descripcion',
        'stock',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
