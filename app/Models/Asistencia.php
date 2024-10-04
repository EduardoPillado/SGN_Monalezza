<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $guard_name = 'web';
    protected $table="asistencia";
    protected $primaryKey='asistencia_pk';
    protected $fillable = [
        'empleado_fk',
        'fecha_asistencia',
        'hora_entrada',
        'hora_salida'
    ];
    public $timestamps=false;
    public function empleado(){
        return $this->belongsTo(Empleado::class, 'empleado_fk');
    }
}
