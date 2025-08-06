<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_ingrediente extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="tipo_ingrediente";
    protected $primaryKey='tipo_ingrediente_pk';
    protected $fillable = [
        'nombre_tipo_ingrediente',
        'estatus_tipo_ingrediente'
    ];
    public $timestamps=false;
    public function ingrediente(){
        return $this->hasMany(Ingrediente::class, 'tipo_ingrediente_fk');
    }
}
