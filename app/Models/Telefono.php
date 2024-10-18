<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="telefono";
    protected $primaryKey='telefono_pk';
    protected $fillable = [
        'telefono'
    ];
    public $timestamps=false;
    public function cliente(){
        return $this->hasMany(Cliente::class, 'telefono_fk');
    }
}
