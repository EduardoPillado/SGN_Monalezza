<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Reserva_mesa;
use App\Models\Mesa;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Reserva_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'cliente_fk' => ['required', 'exists:cliente,cliente_pk'],
            'fecha_hora_reserva' => ['required', 'date', 'after_or_equal:now'],
            'notas' => ['nullable', 'string', 'max:255'],
            'mesas.*' => ['required', 'exists:mesa,mesa_pk'],
        ], [
            'cliente_fk.required' => 'El cliente es obligatorio.',
            'cliente_fk.exists' => 'El cliente seleccionado no es válido.',
        
            'fecha_hora_reserva.required' => 'La fecha y hora de la reserva son obligatorias.',
            'fecha_hora_reserva.date' => 'La fecha y hora de la reserva deben ser una fecha válida.',
            'fecha_hora_reserva.after_or_equal' => 'La fecha y hora de la reserva deben ser en el futuro o en el momento actual.',
        
            'notas.string' => 'Las notas deben ser un texto válido.',
            'notas.max' => 'Las notas no pueden tener más de :max caracteres.',
        
            'mesas.*.required' => 'Es obligatorio seleccionar al menos una mesa.',
            'mesas.*.exists' => 'Una de las mesas seleccionadas no es válida.',
        ]);

        $fechaReserva = Carbon::parse($req->fecha_hora_reserva);
        $finReserva = $fechaReserva->copy()->addHours(1); // Duración estimada de la reservación

        // Verificar conflicto de mesas en reservas ya existentes
        $mesasSolicitadas = $req->mesas ? array_filter($req->mesas) : [];

        $mesasOcupadas = Reserva::join('reserva_mesa', 'reserva.reserva_pk', '=', 'reserva_mesa.reserva_fk')
            ->whereIn('reserva_mesa.mesa_fk', $mesasSolicitadas)
            ->where('estatus_reserva', '!=', 0) // Ignorar las atendidas
            ->where(function ($query) use ($fechaReserva, $finReserva) {
                $query->whereBetween('fecha_hora_reserva', [$fechaReserva, $finReserva])
                    ->orWhereBetween(DB::raw("DATE_ADD(fecha_hora_reserva, INTERVAL 1 HOUR)"), [$fechaReserva, $finReserva])
                    ->orWhere(function($q) use ($fechaReserva, $finReserva) {
                        $q->where('fecha_hora_reserva', '<=', $fechaReserva)
                        ->where(DB::raw("DATE_ADD(fecha_hora_reserva, INTERVAL 1 HOUR)"), '>=', $finReserva);
                    });
            })
            ->where('estatus_reserva', '!=', 2) // Ignorar canceladas
            ->select('reserva_mesa.mesa_fk')
            ->pluck('reserva_mesa.mesa_fk')
            ->unique()
            ->toArray();

        if (!empty($mesasOcupadas)) {
            $nombresMesas = Mesa::whereIn('mesa_pk', $mesasOcupadas)->pluck('numero_mesa')->toArray();
            return back()->withErrors([
                'mesas' => 'Las siguientes mesas ya están reservadas en ese horario: ' . implode(', ', $nombresMesas)
            ])->withInput();
        }

        $reserva=new Reserva();

        $reserva->cliente_fk=$req->cliente_fk;
        $reserva->fecha_hora_reserva=$req->fecha_hora_reserva;
        $reserva->notas=$req->notas;
        $reserva->estatus_reserva=1;

        $reserva->save();

        if ($req->has('mesas')) {
            foreach ($req->mesas as $mesa_pk) {
                $mesas_reserva=new Reserva_mesa();
                $mesas_reserva->reserva_fk=$reserva->reserva_pk;
                $mesas_reserva->mesa_fk=$mesa_pk;
                $mesas_reserva->save();
            }
        }
        
        if ($reserva->reserva_pk) {
            return back()->with('success', 'Reserva registrada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosReserva = Reserva::all();
        $mesas = Mesa::where('estatus_mesa', '=', 1)->get();
        $clientes = Cliente::all();
        $datosMesa = Mesa::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('reservas', compact('datosReserva', 'mesas', 'clientes', 'datosMesa'));
        } else {
            return redirect('/login');
        }
    }

    public function filtrar(Request $req){
        $query = Reserva::with('cliente');

        // Filtrar por fecha
        if ($req->filled('fecha')) {
            $query->whereDate('fecha_hora_reserva', $req->fecha);
        }

        // Filtrar por cliente
        if ($req->filled('cliente_fk')) {
            $query->where('cliente_fk', $req->cliente_fk);
        }

        // Filtrar por estatus
        $estatus = $req->input('estatus');
        // Por estatus de reservación (atendida, pendiente, cancelada)
        if (in_array($estatus, ['0', '1', '2'])) {
            $query->where('estatus_reserva', $estatus);
        }

        $datosReserva = $query->orderBy('fecha_hora_reserva', 'desc')->get();
        $mesas = Mesa::where('estatus_mesa', '=', 1)->get();
        $clientes = Cliente::all();
        $datosMesa = Mesa::all();

        return view('reservas', compact('datosReserva', 'mesas', 'clientes', 'datosMesa'));
    }

    public function pendiente($reserva_pk){
        $datosReserva = Reserva::findOrFail($reserva_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosReserva) {

                $datosReserva->estatus_reserva = 1;
                $datosReserva->save();

                return back()->with('success', 'Cancelación deshecha');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function atendida($reserva_pk){
        $datosReserva = Reserva::findOrFail($reserva_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosReserva) {

                $datosReserva->estatus_reserva = 0;
                $datosReserva->save();

                return back()->with('success', 'Reservación atendida');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function cancelada($reserva_pk){
        $datosReserva = Reserva::findOrFail($reserva_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosReserva) {

                $datosReserva->estatus_reserva = 2;
                $datosReserva->save();

                return back()->with('success', 'Reservación cancelada');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function datosParaEdicion($reserva_pk){
        $datosReserva = Reserva::with('mesas')->findOrFail($reserva_pk);
        $datosMesa = Mesa::all();

        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('editarReserva', compact('datosReserva', 'datosMesa'));
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $reserva_pk){
        $datosReserva = Reserva::findOrFail($reserva_pk);

        $req->validate([
            'cliente_fk' => ['exists:cliente,cliente_pk'],
            'fecha_hora_reserva' => ['date', 'after_or_equal:now'],
            'notas' => ['nullable', 'string', 'max:255'],
            'mesas.*' => ['required', 'exists:mesa,mesa_pk'],
        ], [
            'cliente_fk.exists' => 'El cliente seleccionado no es válido.',
        
            'fecha_hora_reserva.date' => 'La fecha y hora de la reserva deben ser una fecha válida.',
            'fecha_hora_reserva.after_or_equal' => 'La fecha y hora de la reserva deben ser en el futuro o en el momento actual.',
        
            'notas.string' => 'Las notas deben ser un texto válido.',
            'notas.max' => 'Las notas no pueden tener más de :max caracteres.',
        
            'mesas.*.required' => 'Es obligatorio seleccionar al menos una mesa.',
            'mesas.*.exists' => 'Una de las mesas seleccionadas no es válida.',
        ]);

        $fechaReserva = Carbon::parse($req->fecha_hora_reserva);
        $finReserva = $fechaReserva->copy()->addHours(1); // Duración estimada de la reservación

        $mesasSolicitadas = $req->mesas ? array_filter($req->mesas) : [];

        // Validar que ninguna mesa esté ocupada por otra reservación en ese rango
        $mesasOcupadas = Reserva::join('reserva_mesa', 'reserva.reserva_pk', '=', 'reserva_mesa.reserva_fk')
            ->whereIn('reserva_mesa.mesa_fk', $mesasSolicitadas)
            ->where('reserva.reserva_pk', '!=', $reserva_pk) // Ignorar la reservación actual
            ->where('estatus_reserva', '!=', 0) // Ignorar las atendidas
            ->where(function ($query) use ($fechaReserva, $finReserva) {
                $query->whereBetween('fecha_hora_reserva', [$fechaReserva, $finReserva])
                    ->orWhereBetween(DB::raw("DATE_ADD(fecha_hora_reserva, INTERVAL 1 HOUR)"), [$fechaReserva, $finReserva])
                    ->orWhere(function($q) use ($fechaReserva, $finReserva) {
                        $q->where('fecha_hora_reserva', '<=', $fechaReserva)
                        ->where(DB::raw("DATE_ADD(fecha_hora_reserva, INTERVAL 1 HOUR)"), '>=', $finReserva);
                    });
            })
            ->where('estatus_reserva', '!=', 2) // Ignorar canceladas
            ->pluck('reserva_mesa.mesa_fk')
            ->unique()
            ->toArray();

        if (!empty($mesasOcupadas)) {
            $nombresMesas = Mesa::whereIn('mesa_pk', $mesasOcupadas)->pluck('numero_mesa')->toArray();
            return back()->withErrors([
                'mesas' => 'Las siguientes mesas ya están reservadas en ese horario: ' . implode(', ', $nombresMesas)
            ])->withInput();
        }

        $datosReserva->cliente_fk=$req->cliente_fk;
        $datosReserva->fecha_hora_reserva=$req->fecha_hora_reserva;
        $datosReserva->notas=$req->notas;
        $datosReserva->save();

        $mesas = $req->mesas ? array_filter($req->mesas) : [];
        
        Reserva_mesa::where('reserva_fk', $reserva_pk)->delete();

        foreach ($mesas as $mesa_pk) {
            $mesas_reserva = new Reserva_mesa();
            $mesas_reserva->reserva_fk = $reserva_pk;
            $mesas_reserva->mesa_fk = $mesa_pk;
            $mesas_reserva->save();
        }
        
        if ($datosReserva->reserva_pk) {
            return redirect('/reservas')->with('success', 'Datos de reserva actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
