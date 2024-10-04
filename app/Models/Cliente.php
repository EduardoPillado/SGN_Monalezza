<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="cliente";
    protected $primaryKey='cliente_pk';
    protected $fillable = [
        'nombre_cliente',
        'domicilio_fk',
        'telefono_fk'
    ];
    public $timestamps=false;
    public function pedido(){
        return $this->hasMany(Pedido::class, 'cliente_fk');
    }
    public function reserva(){
        return $this->hasMany(Reserva::class, 'cliente_fk');
    }
    public function domicilio(){
        return $this->belongsTo(Domicilio::class, 'domicilio_fk');
    }
    public function telefono(){
        return $this->belongsTo(Telefono::class, 'telefono_fk');
    }
}
