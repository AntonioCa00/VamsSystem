<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizaciones extends Model
{

    protected $fillable = [       
        'id_cotizacion',
        'requisicion_id',
        'usuario_id',
        'pdf',
        'estatus',
        'created_at',        
        'updated_at'
    ];

    use HasFactory;
}
