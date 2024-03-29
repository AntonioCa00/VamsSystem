<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
    protected $fillable = [ 
        'requisicion_id',
        'cantidad',        
        'unidad', 
        'descripcion',
        'estatus',
        'orden_id',
        'created_at',
        'updated_at'
        ];

    use HasFactory;
}
