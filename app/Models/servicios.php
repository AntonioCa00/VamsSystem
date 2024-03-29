<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    protected $fillable = [ 
        'id_servicio',
        'nombre_servicio',   
        'usuario_id',
        'proveedor_id',
        'estatus',
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
