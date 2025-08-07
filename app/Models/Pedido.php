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
        'pago',
        'cambio',
        'estatus_pedido'
    ];
    protected $casts = [
        'fecha_hora_pedido' => 'datetime',
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
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuario_fk');
    }
    public function medio_pedido(){
        return $this->belongsTo(Medio_pedido::class, 'medio_pedido_fk');
    }
    public function tipo_pago(){
        return $this->belongsTo(Tipo_pago::class, 'tipo_pago_fk');
    }
    public function productos(){
        return $this->belongsToMany(Producto::class, 'detalle_pedido', 'pedido_fk', 'producto_fk')
            ->withPivot('cantidad_producto');
    }
    public function mostrarTicket($pedido_pk){
        $pedido = Pedido::with('cliente', 'productos', 'empleado', 'medio_pedido', 'tipo_pago')
            ->findOrFail($pedido_pk);
        return view('ticket', compact('pedido'));
    }
}
