<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomina;
use App\Models\Asistencia;
use App\Models\Empleado;
use Carbon\Carbon;
use Yasumi\Yasumi;

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

    private function obtenerDiasFestivosRango($fechaInicio, $fechaFin) {
        $anios = range($fechaInicio->year, $fechaFin->year);
        $diasFestivos = [];

        foreach ($anios as $anio) {
            try {
                $festivos = Yasumi::create('Mexico', $anio);
                foreach ($festivos as $festivo) {
                    $fechaFestivo = $festivo->format('Y-m-d');
                    if ($fechaFestivo >= $fechaInicio->format('Y-m-d') && $fechaFestivo <= $fechaFin->format('Y-m-d')) {
                        $diasFestivos[] = $fechaFestivo;
                    }
                }
            } catch (\Exception $e) {
                continue; // Manejar años que no se puedan procesar
            }
        }

        return $diasFestivos;
    }

    public function generarNomina(Request $req){
        $req->validate([
            'empleado_fk' => ['required', 'exists:empleado,empleado_pk'],
            'fecha_inicio' => ['required', 'date_format:Y-m-d'],
            'fecha_fin' => ['required', 'date_format:Y-m-d', 'after_or_equal:fecha_inicio'],
            'salario_base' => ['required', 'numeric', 'min:0'],
            'compensacion_extra' => ['nullable', 'numeric', 'min:0'],
        ],[
            'empleado_fk.required' => 'El empleado es obligatorio.',
            'empleado_fk.exists' => 'El empleado seleccionado no es válido.',

            'fecha_inicio.required' => 'La fecha inicial es obligatoria.',
            'fecha_inicio.date' => 'La fecha inicial debe ser una fecha correcta.',

            'fecha_fin.required' => 'La fecha final es obligatoria.',
            'fecha_fin.date' => 'La fecha final debe ser una fecha correcta.',
            'fecha_fin.after_or_equal' => 'La fecha final debe ser posterior o igual a la fecha inicial.',

            'salario_base.required' => 'El salario es obligatorio.',
            'salario_base.numeric' => 'El salario debe ser una cantidad.',
            'salario_base.min' => 'El salario debe ser mayor o igual a 0.',

            'compensacion_extra.numeric' => 'La compensación extra debe ser una cantidad.',
            'compensacion_extra.min' => 'La compensación extra debe ser mayor o igual a 0.',
        ]);
    
        $empleado = Empleado::findOrFail($req->input('empleado_fk'));
        $fechaInicio = Carbon::parse($req->input('fecha_inicio'));
        $fechaFin = Carbon::parse($req->input('fecha_fin'));
        $salarioBase = $req->input('salario_base');
        $compensacionExtra = $req->input('compensacion_extra', 0);

        // Obtener días festivos del periodo
        $diasFestivos = $this->obtenerDiasFestivosRango($fechaInicio, $fechaFin);

        // Filtrar asistencias del empleado en el rango de fechas
        $asistencias = Asistencia::where('empleado_fk', $empleado->empleado_pk)
            ->whereBetween('fecha_asistencia', [$fechaInicio, $fechaFin])
            ->get();

        $horasTrabajadas = 0;
        $horasExtra = 0;
        $horasFestivos = 0;
        $horasRetraso = 0;
        $diasFaltados = 0;

        $horaEntradaEsperada = Carbon::createFromTime(12, 0, 0); // 12:00 p.m.
        $horaSalidaEsperada = Carbon::createFromTime(22, 0, 0); // 10:00 p.m.

        foreach ($asistencias as $asistencia) {
            $fechaAsistencia = Carbon::parse($asistencia->fecha_asistencia);

            // Omitir los miércoles
            if ($fechaAsistencia->isWednesday()) {
                continue;
            }

            $horaEntrada = Carbon::parse($asistencia->hora_entrada);
            $horaSalida = $asistencia->hora_salida ? Carbon::parse($asistencia->hora_salida) : null;

            if ($horaSalida) {
                $horasDia = $horaEntrada->diffInHours($horaSalida);
                $horasTrabajadas += min($horasDia, 10); // Máximo de 10 horas por día
                // Calcular horas extra como horas completas (ignorando minutos)
                $horasExtraDia = max($horasDia - 10, 0);
                $horasExtra += floor($horasExtraDia);

                // Verificar si la fecha es festiva
                if (in_array($fechaAsistencia->format('Y-m-d'), $diasFestivos)) {
                    $horasFestivos += $horasDia;
                }

                // Calcular retrasos en entrada
                if ($horaEntrada > $horaEntradaEsperada) {
                    $retrasoEntrada = ceil($horaEntrada->diffInMinutes($horaEntradaEsperada) / 15) * 0.25;
                    $horasRetraso += $retrasoEntrada;
                }

                // Calcular salida anticipada (si existe hora de salida)
                if ($horaSalida && $horaSalida < $horaSalidaEsperada) {
                    $retrasoSalida = ceil($horaSalidaEsperada->diffInMinutes($horaSalida) / 15) * 0.25;
                    $horasRetraso += $retrasoSalida;
                }
            }
        }

        // Calcular días faltados excluyendo miércoles
        $rangoFechas = collect(Carbon::parse($fechaInicio)->daysUntil($fechaFin));
        $diasAsistidos = $asistencias->pluck('fecha_asistencia')->map(fn($fecha) => Carbon::parse($fecha)->format('Y-m-d'));

        $diasFaltados = $rangoFechas->reject(fn($dia) => 
            $diasAsistidos->contains($dia->format('Y-m-d')) || 
            in_array($dia->format('Y-m-d'), $diasFestivos) || 
            $dia->isWednesday() // Excluir miércoles
        )->count();

        // Ajustar deducciones
        $deduccionesRetrasos = $horasRetraso * 20; // $20 por hora de retraso
        $deduccionesFaltas = $diasFaltados * ($salarioBase / 30); // Deducción proporcional diaria
        $deducciones = $deduccionesRetrasos + $deduccionesFaltas;

        // Calcular salarios
        $salarioHorasExtra = $horasExtra * 50; // $50 por hora extra
        $salarioDiasFestivos = $horasFestivos * (($salarioBase / 240) * 2); // Doble paga en días festivos
        $salarioNeto = $salarioBase + $salarioHorasExtra + $salarioDiasFestivos + $compensacionExtra - $deducciones;

        // Guardar en la tabla de nómina
        $nomina = new Nomina();
        $nomina->empleado_fk = $empleado->empleado_pk;
        $nomina->fecha_pago = Carbon::now()->format('Y-m-d');
        $nomina->salario_base = $salarioBase;
        $nomina->horas_extra = $horasExtra;
        $nomina->deducciones = $deducciones;
        $nomina->compensacion_extra = $compensacionExtra;
        $nomina->salario_neto = $salarioNeto;
        $nomina->save();

        if ($nomina->nomina_pk) {
            return back()->with('success', 'Nómina generada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
