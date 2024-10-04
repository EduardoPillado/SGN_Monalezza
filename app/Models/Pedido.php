<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="pedido";
    protected $primaryKey='pedido_pk';
    protected $fillable = [
        'cliente_fk',
        'empleado_fk',
        'fecha_hora_pedido',
        'medio_pedido_fk',
        'monto_total',
        'numero_transaccion',
        'tipo_pago_fk',
        'notas_remision',
        'estatus_pedido'
    ];
    public $timestamps=false;
    public function detalle_pedido(){
        return $this->hasMany(Detalle_pedido::class, 'pedido_fk');
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_fk');
    }
    public function empleado(){
        return $this->belongsTo(Empleado::class, 'empleado_fk');
    }
    public function medio_pedido(){
        return $this->belongsTo(Medio_pedido::class, 'medio_pedido_fk');
    }
    public function tipo_pago(){
        return $this->belongsTo(Tipo_pago::class, 'tipo_pago_fk');
    }
}
