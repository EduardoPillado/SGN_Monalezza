<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomina;
use App\Models\Asistencia;
use App\Models\Empleado;
use Carbon\Carbon;

class Nomina_controller extends Controller
{
    public function mostrar(){
        $datosNomina = Nomina::all();
        $empleados = Empleado::where('estatus_empleado', '=', 1)->get();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('nomina', compact('datosNomina', 'empleados'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function generarNomina(Request $req){
        $req->validate([
            'empleado_fk' => ['required', 'exists:empleado,empleado_pk'],
            'fecha_inicio' => ['required', 'date_format:Y-m-d'],
            'fecha_fin' => ['required', 'date_format:Y-m-d', 'after_or_equal:fecha_inicio'],
            'salario_base' => ['required', 'numeric', 'min:0'],
        ],[
            'empleado_fk.required' => 'El empleado es obligatorio.',
            'empleado_fk.exists' => 'El empleado seleccionado no es válido.',

            'fecha_inicio.required' => 'La fecha inicial es obligatoria.',
            'fecha_inicio.date' => 'La fecha inicial debe ser una fecha.',

            'fecha_fin.required' => 'La fecha final es obligatoria.',
            'fecha_fin.date' => 'La fecha final debe ser una fecha.',
            'fecha_fin.after_or_equal' => 'La fecha final debe ser posterior o igual a la fecha inicial.',

            'salario_base.required' => 'El salario es obligatorio.',
            'salario_base.numeric' => 'El salario debe ser un número.',
            'salario_base.min' => 'El salario debe ser mayor o igual a 0.',
        ]);
    
        $empleado = Empleado::findOrFail($req->input('empleado_fk'));
        $fechaInicio = Carbon::parse($req->input('fecha_inicio'));
        $fechaFin = Carbon::parse($req->input('fecha_fin'));
        $salarioBase = $req->input('salario_base');

        $asistencias = Asistencia::where('empleado_fk', $empleado->empleado_pk)
            ->whereBetween('fecha_asistencia', [$fechaInicio, $fechaFin])
            ->get();

        $horasTrabajadas = 0;
        $horasExtra = 0;
        $minutosTarde = 0;
        $diasLaborables = $fechaInicio->diffInWeekdays($fechaFin);
        $diasAusentes = $diasLaborables - $asistencias->count();

        foreach ($asistencias as $asistencia) {
            $horaEntrada = Carbon::parse($asistencia->hora_entrada);
            $horaSalida = $asistencia->hora_salida ? Carbon::parse($asistencia->hora_salida) : null;
            $horaInicioLaboral = Carbon::parse('09:00'); // Ejemplo de hora de entrada laboral

            if ($horaSalida) {
                $horasDia = $horaEntrada->diffInHours($horaSalida);
                $horasTrabajadas += min($horasDia, 8);
                $horasExtra += max($horasDia - 8, 0);

                // Calcular minutos de retraso
                if ($horaEntrada->greaterThan($horaInicioLaboral)) {
                    $minutosTarde += $horaInicioLaboral->diffInMinutes($horaEntrada);
                }
            }
        }

        // Deducciones
        $deduccionTardanza = $minutosTarde * 0.5; // Ejemplo: $0.5 por cada minuto tarde
        $deduccionAusencia = $diasAusentes * ($salarioBase / $diasLaborables); // Deducción por día de ausencia
        $deducciones = $deduccionTardanza + $deduccionAusencia;

        $salarioExtra = $horasExtra * ($salarioBase / 160);
        $salarioNeto = $salarioBase + $salarioExtra - $deducciones;

        // Crear un registro en la tabla `nomina`
        $nomina = new Nomina();
        $nomina->empleado_fk = $empleado->empleado_pk;
        $nomina->fecha_pago = Carbon::now()->format('Y-m-d');
        $nomina->salario_base = $salarioBase;
        $nomina->horas_extra = $horasExtra;
        $nomina->deducciones = $deducciones;
        $nomina->salario_neto = $salarioNeto;
        $nomina->save();

        if ($nomina->nomina_pk) {
            return back()->with('success', 'Nómina generada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
