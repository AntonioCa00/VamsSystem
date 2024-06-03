<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unidadServicio extends Model
{
    protected $fillable = [
        'id_unidad_serv',
        'unidad_id',
        'km_mantenimiento',
        'contador',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
