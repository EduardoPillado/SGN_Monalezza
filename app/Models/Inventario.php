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
        'producto_fk',
        'fecha_inventario',
        'cantidad_inventario',
        'gasto_fk'
    ];
    public $timestamps=false;
    public function ingrediente(){
        return $this->belongsTo(Ingrediente::class, 'ingrediente_fk');
    }
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_fk');
    }
    public function gasto(){
        return $this->belongsTo(Gasto::class, 'gasto_fk');
    }
}
