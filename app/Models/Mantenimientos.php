<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimientos extends Model
{

    protected $fillable = [
        'id_mantenimiento',
        'nombre',
        'descripcion',
        'created_at',
        'updated_at'
    ]
    use HasFactory;
}
