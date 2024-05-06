<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CamionServicioPreventivo extends Model
{   
    protected $table = 'camion_servicios_preventivos';

    protected $fillable = [  
        'id_servicio_preventivo',
        'unidad_id',
        'filtro_aire_grande',
        'filtro_aire_chico',
        'filtro_diesel',
        'filtro_aceite',
        'wk1016_trampa',
        'aceite_motor',
        'filtro_urea',
        'anticongelante',
        'aceite_direccion',
        'banda_poles',
        'ajuste_frenos',
        'engrasado_chasis',
        'created_at',
        'updated_at'
    ];

    use HasFactory;
}

