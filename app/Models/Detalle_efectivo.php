<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_efectivo extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="detalle_efectivo";
    protected $primaryKey='detalle_efectivo_pk';
    protected $fillable = [
        'fecha_actual',
        'efectivo_inicial'
    ];
    public $timestamps=false;
}
