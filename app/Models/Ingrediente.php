<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="ingrediente";
    protected $primaryKey='ingrediente_pk';
    protected $fillable = [
        'nombre_ingrediente',
        'cantidad_actual',
        'um',
        'um_minima',
        'estatus_ingrediente'
    ];
    public $timestamps=false;
}
