<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="gasto";
    protected $primaryKey='gasto_pk';
    protected $fillable = [
        'fecha_gasto',
        'tipo_gasto_fk',
        'monto_gasto',
        'proveedor_fk',
        'descripcion'
    ];
    public $timestamps=false;
    public function inventario(){
        return $this->hasMany(Inventario::class, 'gasto_fk');
    }
    public function tipo_gasto(){
        return $this->belongsTo(Tipo_gasto::class, 'tipo_gasto_fk');
    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_fk');
    }
}
