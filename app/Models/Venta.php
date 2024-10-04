<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="venta";
    protected $primaryKey='venta_pk';
    protected $fillable = [
        'cantidad_venta',
        'monto_venta'
    ];
    public $timestamps=false;
    public function detalle_pedido(){
        return $this->hasMany(Detalle_pedido::class, 'venta_fk');
    }
}
