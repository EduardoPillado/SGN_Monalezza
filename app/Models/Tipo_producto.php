<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_producto extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="tipo_producto";
    protected $primaryKey='tipo_producto_pk';
    protected $fillable = [
        'nombre_tipo_producto',
        'estatus_tipo_producto'
    ];
    public $timestamps=false;
}
