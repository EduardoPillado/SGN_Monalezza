<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="gasto";
    protected $primaryKey='gasto_pk';
    protected $fillable = [
        'fecha_gasto',
        'tipo_gasto',
        'monto_gasto',
        'proveedor_fk',
        'descripcion'
    ];
    public $timestamps=false;
}
