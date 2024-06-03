<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historialMant extends Model
{

    protected $fillable = [       
        'id_historial',
        'programacion_id',
        'unidad_id',
        'mantenimiento_id',
        'estatus',
        'km_final',
        'ciclo',
        'notas',
        'created_at',        
        'updated_at'
    ];

    use HasFactory;
}
