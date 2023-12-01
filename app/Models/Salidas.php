<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{

    protected $fillable = [
        'id_salida',
        'requisicion_id',
        'cantidad',
        'usuario_id',
        'refaccion_id',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
