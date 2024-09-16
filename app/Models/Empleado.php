<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="empleado";
    protected $primaryKey='empleado_pk';
    protected $fillable = [
        'usuario_fk',
        'fecha_contratacion',
        'estatus_empleado'
    ];
    public $timestamps=false;
}
