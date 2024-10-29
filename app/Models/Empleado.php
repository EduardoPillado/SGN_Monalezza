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
    public function asistencia(){
        return $this->hasMany(Asistencia::class, 'empleado_fk');
    }
    public function pedido(){
        return $this->hasMany(Pedido::class, 'empleado_fk');
    }
    public function nomina(){
        return $this->hasMany(Nomina::class, 'empleado_fk');
    }
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuario_fk');
    }
    public function corte_empleado(){
        return $this->hasMany(Corte_empleado::class, 'empleado_fk');
    }
}
