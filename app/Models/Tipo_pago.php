<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_pago extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="tipo_pago";
    protected $primaryKey='tipo_pago_pk';
    protected $fillable = [
        'nombre_tipo_pago',
        'estatus_tipo_pago'
    ];
    public $timestamps=false;
}
