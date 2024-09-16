<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="inventario";
    protected $primaryKey='inventario_pk';
    protected $fillable = [
        'ingrediente_fk',
        'fecha_inventario',
        'cantidad_inventario'
    ];
    public $timestamps=false;
}
