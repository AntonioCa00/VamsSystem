<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{

    protected $fillable = [
        'id_salida',
        'requisicion_id',
        'usuario_id',
        'cantidad',        
        'refaccion_id',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
