<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="ingrediente";
    protected $primaryKey='ingrediente_pk';
    protected $fillable = [
        'nombre_ingrediente',
        'cantidad_actual',
        'um',
        'um_minima',
        'estatus_ingrediente'
    ];
    public $timestamps=false;
    public function detalle_ingrediente(){
        return $this->hasMany(Detalle_ingrediente::class, 'ingrediente_fk');
    }
    public function inventario(){
        return $this->hasMany(Inventario::class, 'ingrediente_fk');
    }
    public function productos() {
        return $this->belongsToMany(Producto::class, 'producto_ingrediente', 'ingrediente_fk', 'producto_fk');
    }
}
