<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_ingrediente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="detalle_ingrediente";
    protected $primaryKey='detalle_ingrediente_pk';
    protected $fillable = [
        'producto_fk',
        'ingrediente_fk',
        'cantidad_usada'
    ];
    public $timestamps=false;
}
