<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="usuario";
    protected $primaryKey='usuario_pk';
    protected $fillable = [
        'nombre',
        'rol_fk',
        'usuario',
        'contraseña',
        'estatus_usuario'
    ];
    public $timestamps=false;
}