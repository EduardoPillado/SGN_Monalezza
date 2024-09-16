<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto_ingrediente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="producto_ingrediente";
    protected $primaryKey='producto_ingrediente_pk';
    protected $fillable = [
        'producto_fk',
        'ingrediente_fk'
    ];
    public $timestamps=false;
}
