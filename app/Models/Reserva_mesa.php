<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva_mesa extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="reserva_mesa";
    protected $primaryKey='reserva_mesa_pk';
    protected $fillable = [
        'mesa_fk',
        'reserva_fk'
    ];
    public $timestamps=false;
}