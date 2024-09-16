<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="nomina";
    protected $primaryKey='nomina_pk';
    protected $fillable = [
        'empleado_fk',
        'fecha_pago',
        'salario_base',
        'horas_extra',
        'deducciones',
        'salario_neto'
    ];
    public $timestamps=false;
}
