<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_gasto extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="tipo_gasto";
    protected $primaryKey='tipo_gasto_pk';
    protected $fillable = [
        'nombre_tipo_gasto',
        'estatus_tipo_gasto'
    ];
    public $timestamps=false;
    public function gasto(){
        return $this->hasMany(Gasto::class, 'tipo_gasto_fk');
    }
}
