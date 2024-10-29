<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corte_empleado extends Model
{
    use HasFactory;
    protected $table="corte_empleado";
    protected $primaryKey='corte_empleado_pk';
    protected $fillable = [
        'corte_caja_fk',
        'empleado_fk'
    ];
    public $timestamps=false;
    public function corte_caja(){
        return $this->belongsTo(Corte_caja::class, 'corte_caja_fk');
    }
    public function empleado(){
        return $this->belongsTo(Empleado::class, 'empleado_fk');
    }
}
