<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{

    protected $table = 'logs';

    protected $fillable = [       
        'user_id',
        'requisicion_id',
        'table_name',
        'action',
        'created_at',        
        'updated_at'
    ];

    use HasFactory;
}
