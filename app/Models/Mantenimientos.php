<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mantenimientos extends Model
{
    protected $fillable = [
        'id_mantenimiento',
        'nombre',
        'descripcion',
        'status',
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
