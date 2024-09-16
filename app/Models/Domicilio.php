<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domicilio extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="domicilio";
    protected $primaryKey='domicilio_pk';
    protected $fillable = [
        'calle',
        'numero_externo',
        'numero_interno',
        'referencias',
        'estatus_domicilio'
    ];
    public $timestamps=false;
}
