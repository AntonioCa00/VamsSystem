<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servicios extends Model
{
    protected $fillable = [
        'id_servicio',
        'nombre_servicio',
        'descripcion',
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
