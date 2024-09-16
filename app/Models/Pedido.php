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
        'medio_pedido',
        'monto_total',
        'numero_transaccion',
        'tipo_pago_fk',
        'notas_remision',
        'estatus_pedido'
    ];
    public $timestamps=false;
}
