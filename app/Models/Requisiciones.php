<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisiciones extends Model
{

    protected $fillable = [ 
    'id_requisicion',
        'usuario_id',        
        'unidad_id',
        'pdf',
        'estado',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
