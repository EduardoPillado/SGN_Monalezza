<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="producto";
    protected $primaryKey='producto_pk';
    protected $fillable = [
        'nombre_producto',
        'tipo_producto_fk',
        'precio_producto',
        'estatus_producto'
    ];
    public $timestamps=false;
    public function detalle_ingrediente(){
        return $this->hasMany(Detalle_ingrediente::class, 'producto_fk');
    }
    public function detalle_pedido(){
        return $this->hasMany(Detalle_pedido::class, 'producto_fk');
    }
    public function inventario(){
        return $this->hasMany(Inventario::class, 'producto_fk');
    }
    public function ingredientesPersonalizados(){
        return $this->hasManyThrough(
            Detalle_pedido_ingrediente::class,  // Tabla relacionada (destino)
            Detalle_pedido::class,              // Tabla intermedia (a través de)
            'producto_fk',                     // Foreign key en tabla intermedia (detalle_pedido)
            'detalle_pedido_fk',              // Foreign key en tabla final (detalle_pedido_ingrediente)
            'producto_pk',                     // Local key en tabla inicial (producto)
            'detalle_pedido_pk'          // Local key en tabla intermedia (detalle_pedido)
        );
    }
    public function ingredientes() {
        return $this->belongsToMany(Ingrediente::class, 'detalle_ingrediente', 'producto_fk', 'ingrediente_fk')
            ->withPivot('cantidad_necesaria');
    }
    public function tipo_producto(){
        return $this->belongsTo(Tipo_producto::class, 'tipo_producto_fk');
    }
}
