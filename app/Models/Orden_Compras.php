<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden_Compras extends Model
{
    protected $table = 'orden_compras';

    protected $fillable = [       
        'id_orden',
        'admin_id',
        'cotizacion_id',
        'proveedor_id',
        'costo_total',
        'pdf',
        'created_at',        
        'updated_at'
    ];

    use HasFactory;
}
