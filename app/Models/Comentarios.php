<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{

    protected $fillable = [       
        'requisicion_id',
        'usuario_id',
        'detalles',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
