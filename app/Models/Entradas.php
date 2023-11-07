<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entradas extends Model
{

    protected $fillable = [       
        'id_entrada',
        'orden_id',
        'factura',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
