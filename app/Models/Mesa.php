<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="mesa";
    protected $primaryKey='mesa_pk';
    protected $fillable = [
        'numero_mesa',
        'ubicacion'
    ];
    public $timestamps=false;
}
