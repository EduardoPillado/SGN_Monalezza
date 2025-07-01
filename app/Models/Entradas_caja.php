<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entradas_caja extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="entradas_caja";
    protected $primaryKey='entradas_caja_pk';
    protected $fillable = [
        'monto',
        'concepto',
        'fecha_entrada',
        'usuario_fk'
    ];
    public $timestamps=false;
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuario_fk');
    }
}
