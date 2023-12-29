<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{

    protected $table = 'almacen';

    protected $fillable = [       
        'clave',
        'ubicacion',
        'descripcion',
        'medida',
        'marca',
        'cantidad',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
