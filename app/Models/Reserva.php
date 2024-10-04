<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="reserva";
    protected $primaryKey='reserva_pk';
    protected $fillable = [
        'cliente_fk',
        'fecha_hora_reserva',
        'notas',
        'estatus_reserva'
    ];
    public $timestamps=false;
    public function mesas() {
        return $this->belongsToMany(Mesa::class, 'reserva_mesa', 'reserva_fk', 'mesa_fk');
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_fk');
    }
}
