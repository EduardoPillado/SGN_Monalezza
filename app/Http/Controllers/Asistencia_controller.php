<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class Asistencia_controller extends Controller
{
    public function mostrar(){
        $datosAsistencia = Asistencia::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('asistencias', compact('datosAsistencia'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function entrada(){
        $datosEntrada = Asistencia::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('entrada', compact('datosEntrada'));
        } else {
            return redirect('/login');
        }
    }

    public function registrarEntrada(Request $req){
        $req->validate([
            'empleado_fk' => ['required'],
            'fecha_asistencia' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('asistencia')->where(function ($query) use ($req) {
                    return $query->where('empleado_fk', $req->empleado_fk)
                                ->where('fecha_asistencia', Carbon::now()->format('Y-m-d'));
                })
            ],
            'hora_entrada' => ['required', 'date_format:H:i'],
        ],[
            'empleado_fk.required' => 'El empleado es obligatorio.',

            'fecha_asistencia.required' => 'La fecha de asistencia es obligatoria.',
            'fecha_asistencia.date_format' => 'La fecha de asistencia debe ser una fecha válida.',
            'fecha_asistencia.unique' => 'Ya hay un registro de entrada para este empleado el día de hoy.',

            'hora_entrada.required' => 'La hora de entrada es obligatoria.',
            'hora_entrada.date_format' => 'La hora de entrada debe ser una hora válida.',
        ]);

        $asistencia = new Asistencia();
        $asistencia->empleado_fk = $req->empleado_fk;
        $asistencia->fecha_asistencia = $req->fecha_asistencia;
        $asistencia->hora_entrada = $req->hora_entrada;
        $asistencia->save();

        if ($asistencia->asistencia_pk) {
            return redirect('/')->with('success', 'Entrada registrada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function salida(){
        $USUARIO_PK = session('usuario_pk');
        if (!$USUARIO_PK) {
            return redirect('/login');
        }

        $empleado = Empleado::where('usuario_fk', $USUARIO_PK)->first();
        if (!$empleado) {
            return back()->with('error', 'No se encontró un empleado relacionado con el usuario actual.');
        }

        $asistencia = Asistencia::where('empleado_fk', $empleado->empleado_pk)
            ->whereDate('fecha_asistencia', Carbon::now()->format('Y-m-d'))
            ->whereNull('hora_salida')
            ->first();
        
        $ROL = session('nombre_rol');
        if (!$asistencia && $ROL == 'Administrador') {
            $asistencia = new Asistencia();
            $asistencia->empleado_fk = $empleado->empleado_pk;
            $asistencia->fecha_asistencia = Carbon::now()->format('Y-m-d');
            $asistencia->hora_entrada = null;
            $asistencia->save();
        }

        if (!$asistencia) {
            return back()->with('error', 'No se encontró un registro de entrada para el día actual.');
        }

        return view('salida', compact('asistencia'));
    }

    public function registrarSalida(Request $req){
        $req->validate([
            'hora_salida' => ['required', 'date_format:H:i', 'after:hora_entrada'],
        ], [
            'hora_salida.required' => 'La hora de salida es obligatoria.',
            'hora_salida.date_format' => 'La hora de salida debe ser una hora válida.',
            'hora_salida.after' => 'La hora de salida debe ser posterior a la hora de entrada.',
        ]);

        $empleado_fk = session('usuario_pk');
        if (!$empleado_fk) {
            return redirect('/login');
        }

        $ROL = session('nombre_rol');
        if ($ROL == 'Administrador') {
            $empleado_fk = $req->input('empleado_fk');
            $fecha = $req->input('fecha_asistencia');

            // Buscar asistencia existente o crear una nueva si no hay
            $asistencia = Asistencia::firstOrNew([
                'empleado_fk' => $empleado_fk,
                'fecha_asistencia' => $fecha,
            ]);

            // Solo asignar hora_salida; entrada puede estar vacía
            $asistencia->hora_salida = $req->hora_salida;
            $asistencia->save();

            return redirect('/')->with('success', 'Salida registrada como administrador.');
        }

        $asistencia = Asistencia::where('empleado_fk', $empleado_fk)
                        ->whereDate('fecha_asistencia', Carbon::now()->format('Y-m-d'))
                        ->whereNull('hora_salida')
                        ->first();

        if (!$asistencia) {
            return back()->with('error', 'No se encontró un registro de entrada para actualizar la salida.');
        }

        $asistencia->hora_salida = $req->hora_salida;
        $asistencia->save();

        if ($asistencia->asistencia_pk) {
            return redirect('/')->with('success', 'Salida registrada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
