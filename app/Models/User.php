<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $fillable = [
        'id',
        'nombre',
        'telefono',
        'correo',
        'password',
        'rol',
        'departamento',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
