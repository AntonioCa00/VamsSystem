<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{

    protected $fillable = [
        'id_proveedor',
        'nombre',
        'sobrenombre',
        'regimen_fiscal',
        'telefono',
        'telefono2',
        'contacto',
        'direccion',
        'domicilio',
        'rfc',
        'correo',
        'CIF',
        'banco',
        'n_cuenta',
        'n_cuenta_clabe',         
        'estado_cuenta',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
