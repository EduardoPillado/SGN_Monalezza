<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corte_caja extends Model
{
    use HasFactory;
    protected $table="corte_caja";
    protected $primaryKey='corte_caja_pk';
    protected $fillable = [
        'fecha_corte_inicio',
        'fecha_corte_fin',
        'cantidad_ventas',
        'ganancia_total'
    ];
    public $timestamps=false;
    public function empleados() {
        return $this->belongsToMany(Empleado::class, 'corte_empleado', 'corte_caja_fk', 'empleado_fk');
    }
    public function corte_empleado(){
        return $this->hasMany(Corte_empleado::class, 'corte_caja_fk');
    }
}
