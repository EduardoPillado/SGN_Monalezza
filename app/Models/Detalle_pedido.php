<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_pedido extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="detalle_pedido";
    protected $primaryKey='detalle_pedido_pk';
    protected $fillable = [
        'pedido_fk',
        'producto_fk',
        'cantidad_producto'
    ];
    public $timestamps=false;
    public function pedido(){
        return $this->belongsTo(Pedido::class, 'pedido_fk');
    }
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_fk');
    }
}
