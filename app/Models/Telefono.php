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
        'telefono',
        'estatus_telefono'
    ];
    public $timestamps=false;
}
