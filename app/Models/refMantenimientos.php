<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class refMantenimientos extends Model
{

    protected $fillable = [
        'id_refaccion_mant',
        'nombre',
        'cantidad',
        'unidad_medida',
        'created_at',
        'updated_at'
    ]
    use HasFactory;
}
