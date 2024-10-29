<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="servicio";
    protected $primaryKey='servicio_pk';
    protected $fillable = [
        'tipo_gasto_fk',
        'cantidad_pagada_servicio',
        'fecha_pago_servicio'
        
    ];
    public $timestamps=false;
    public function tipo_gasto(){
        return $this->belongsTo(Tipo_gasto::class, 'tipo_gasto_fk');
    }
}
