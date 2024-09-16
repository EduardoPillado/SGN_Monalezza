<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="proveedor";
    protected $primaryKey='proveedor_pk';
    protected $fillable = [
        'nombre_proveedor',
        'estatus_proveedor'
    ];
    public $timestamps=false;
}
