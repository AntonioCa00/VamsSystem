<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class programaciones extends Model
{
    protected $fillable = [
        'id_programacion',
        'fecha_progra',
        'unidad_id',
        'notas',
        'estatus',
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
