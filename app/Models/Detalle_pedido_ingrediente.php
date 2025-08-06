<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_pedido_ingrediente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="detalle_pedido_ingrediente";
    protected $primaryKey='detalle_pedido_ingrediente_pk';
    protected $fillable = [
        'detalle_pedido_fk',
        'ingrediente_fk',
        'cantidad_usada'
    ];
    public $timestamps=false;
    public function detalle_pedido(){
        return $this->belongsTo(Detalle_pedido::class, 'detalle_pedido_fk');
    }
    public function ingrediente(){
        return $this->belongsTo(Ingrediente::class, 'ingrediente_fk');
    }
}
