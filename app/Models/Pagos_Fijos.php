<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos_Fijos extends Model
{
    protected $table = 'pagos_fijos'; 

    protected $fillable = [ 
        'id_pago',
        'servicio_id',
        'usuario_id',
        'costo_total',
        'pdf',
        'estado',
        'notas',
        'comprobante_pago',
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
