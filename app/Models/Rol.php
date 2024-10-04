<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="rol";
    protected $primaryKey='rol_pk';
    protected $fillable = [
        'nombre_rol',
        'permisos'
    ];
    public $timestamps=false;
    public function usuario(){
        return $this->hasMany(Usuario::class, 'rol_fk');
    }
}
