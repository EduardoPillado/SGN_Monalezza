<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medio_pedido extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="medio_pedido";
    protected $primaryKey='medio_pedido_pk';
    protected $fillable = [
        'nombre_medio_pedido',
        'estatus_medio_pedido'
    ];
    public $timestamps=false;
    public function pedido(){
        return $this->hasMany(Pedido::class, 'medio_pedido_fk');
    }
}
