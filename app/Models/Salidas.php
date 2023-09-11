<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{

    protected $fillable = [
        'id_ssalida',
        'solicitud_id',
        'cantidad',
        'estatus',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}
