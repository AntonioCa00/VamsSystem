<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitudes extends Model
{
    protected $fillable = [
        'id_solicitud',
        'encargado_id',
        'estado',
        'unidad_id',
        'descripcion',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
