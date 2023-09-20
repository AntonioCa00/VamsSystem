<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizaciones extends Model
{

    protected $fillable = [       
        'id_cotizacio',
        'solicitud_id',
        'administrador_id',
        'Proveedor',
        'Costo_total',
        'archivo_pdf',
        'estatus',
        'created_at',        
        'updated_at'
    ];

    use HasFactory;
}
