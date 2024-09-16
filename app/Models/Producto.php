<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="producto";
    protected $primaryKey='producto_pk';
    protected $fillable = [
        'nombre_producto',
        'tipo_producto_fk',
        'precio_producto',
        'proveedor_fk',
        'estatus_producto'
    ];
    public $timestamps=false;
}
