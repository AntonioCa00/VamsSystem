<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{

    protected $fillable = [
        'id_proveedor',
        'nombre',
        'telefono',
        'correo',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
