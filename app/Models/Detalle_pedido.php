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
        'venta_fk',
        'cantidad_producto',
        'precio_unitario'
    ];
    public $timestamps=false;
}
