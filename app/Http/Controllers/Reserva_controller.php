<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Reserva_mesa;
use App\Models\Mesa;

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
        $datosReserva = Reserva::with('mesas')->get();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('reservas', compact('datosReserva'));
        } else {
            return redirect('/login');
        }
    }

    public function baja($reserva_pk){
        $datosReserva = Reserva::findOrFail($reserva_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosReserva) {

                $datosReserva->estatus_reserva = 0;
                $datosReserva->save();

                return back()->with('success', 'Reserva dada de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($reserva_pk){
        $datosReserva = Reserva::findOrFail($reserva_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosReserva) {

                $datosReserva->estatus_reserva = 1;
                $datosReserva->save();

                return back()->with('success', 'Reserva dada de alta');
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
            'mesas.*' => ['exists:mesa,mesa_pk'],
        ], [
            'cliente_fk.exists' => 'El cliente seleccionado no es válido.',
        
            'fecha_hora_reserva.date' => 'La fecha y hora de la reserva deben ser una fecha válida.',
            'fecha_hora_reserva.after_or_equal' => 'La fecha y hora de la reserva deben ser en el futuro o en el momento actual.',
        
            'notas.string' => 'Las notas deben ser un texto válido.',
            'notas.max' => 'Las notas no pueden tener más de :max caracteres.',
        
            'mesas.*.exists' => 'Una de las mesas seleccionadas no es válida.',
        ]);

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
