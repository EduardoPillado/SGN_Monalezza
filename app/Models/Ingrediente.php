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
        'tipo_ingrediente_fk',
        'cantidad_paquete',
        'estatus_ingrediente'
    ];
    public $timestamps=false;
    public function detalle_ingrediente(){
        return $this->hasMany(Detalle_ingrediente::class, 'ingrediente_fk');
    }
    public function inventario(){
        return $this->hasMany(Inventario::class, 'ingrediente_fk');
    }
    public function detalle_pedido_ingrediente(){
        return $this->hasMany(Detalle_pedido_ingrediente::class, 'ingrediente_fk');
    }
    public function tipo_ingrediente(){
        return $this->belongsTo(Tipo_ingrediente::class, 'tipo_ingrediente_fk');
    }
    public function productos() {
        return $this->belongsToMany(Producto::class, 'detalle_ingrediente', 'ingrediente_fk', 'producto_fk');
    }
}
