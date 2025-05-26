<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="inventario";
    protected $primaryKey='inventario_pk';
    protected $fillable = [
        'ingrediente_fk',
        'producto_fk',
        'tipo_gasto_fk',
        'proveedor_fk',
        'precio_proveedor',
        'fecha_inventario',
        'cantidad_inventario',
        'cantidad_paquete',
        'cantidad_inventario_minima'
        
    ];
    public $timestamps=false;
    public function ingrediente(){
        return $this->belongsTo(Ingrediente::class, 'ingrediente_fk');
    }
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_fk');
    }
    public function tipo_gasto(){
        return $this->belongsTo(Tipo_gasto::class, 'tipo_gasto_fk');
    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_fk');
    }
}
