<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entradas_caja;
use App\Models\Servicio;
use App\Models\Pedido;
use Carbon\Carbon;

class Entradas_caja_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'monto_entrada_caja' => ['required', 'numeric', 'min:0.01'],
            'tipo_entrada_caja' => ['required', 'in:Inicial,Entrada'],
            'concepto_entrada_caja' => ['nullable', 'string', 'max:255'],
            'fecha_entrada_caja' => ['required', 'date'],
        ], [
            'monto_entrada_caja.required' => 'El monto es obligatorio.',
            'monto_entrada_caja.numeric' => 'El monto debe ser un número válido.',
            'monto_entrada_caja.min' => 'El monto debe ser mayor a cero.',

            'tipo_entrada_caja.required' => 'El tipo de entrada es obligatorio.',
            'tipo_entrada_caja.in' => 'El tipo debe ser "Inicial" o "Entrada".',

            'concepto_entrada_caja.max' => 'El concepto no puede tener más de :max caracteres.',

            'fecha_entrada_caja.required' => 'La fecha de entrada es obligatoria.',
            'fecha_entrada_caja.date' => 'Debe ingresar una fecha válida.',
        ]);

        $usuario_pk = session('usuario_pk');
        if (!$usuario_pk) {
            return redirect('/login');
        }
        
        $fecha = now()::parse($req->fecha_entrada_caja)->toDateString();
        $yaHayInicial = Entradas_caja::whereDate('fecha_entrada_caja', $fecha)
            ->where('tipo_entrada_caja', 'Inicial')
            ->exists();

        $tipo_final = ($req->tipo_entrada_caja === 'Inicial' && $yaHayInicial) ? 'Entrada' : $req->tipo_entrada_caja;

        $entrada_caja = new Entradas_caja();
        $entrada_caja->monto_entrada_caja = $req->monto_entrada_caja;
        $entrada_caja->tipo_entrada_caja = $tipo_final;
        $entrada_caja->concepto_entrada_caja = $req->concepto_entrada_caja;
        $entrada_caja->fecha_entrada_caja = $req->fecha_entrada_caja;
        $entrada_caja->usuario_fk = $usuario_pk;

        $entrada_caja->save();

        if ($entrada_caja->entradas_caja_pk) {
            if ($req->tipo_entrada_caja === 'Inicial' && $yaHayInicial) {
                return back()->with('warning', 'Ya existía un ingreso inicial para ese día. Se registró como entrada.');
            }
            return back()->with('success', 'Entrada de caja registrada correctamente.');
        } else {
            return back()->with('error', 'Hubo un problema al guardar la entrada.');
        }
    }

    public function mostrar(){
        $datosEntradaCaja = Entradas_caja::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('entradasDeCaja', compact('datosEntradaCaja'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public static function calcularResumenCajaHoy(){
        $hoy = Carbon::now()->toDateString();

        $dineroInicial = Entradas_caja::whereDate('fecha_entrada_caja', $hoy)
            ->where('tipo_entrada_caja', 'Inicial')
            ->sum('monto_entrada_caja');

        $entradas = Entradas_caja::whereDate('fecha_entrada_caja', $hoy)
            ->where('tipo_entrada_caja', 'Entrada')
            ->sum('monto_entrada_caja');

        $gastos = Servicio::whereDate('fecha_pago_servicio', $hoy)
            ->sum('cantidad_pagada_servicio');

        $ventas = Pedido::whereDate('fecha_hora_pedido', $hoy)
            ->where('estatus_pedido', 0) // entregado
            ->sum('monto_total');

        $totalCaja = $dineroInicial + $entradas + $ventas - $gastos;

        return [
            'dinero_inicial' => $dineroInicial,
            'entradas' => $entradas,
            'ventas' => $ventas,
            'gastos' => $gastos,
            'total_caja' => $totalCaja
        ];
    }

    public function efectivoInicial(Request $req)
    {
         $validated = $req->validate([
            'monto_entrada_caja' => 'required|numeric|min:0.01'
        ], [
            'monto_entrada_caja.required' => 'La apertura de caja es obligatoria.',
            'monto_entrada_caja.numeric' => 'Debe ser una cantidad numérica.',
            'monto_entrada_caja.min' => 'Debe ser mayor o igual a 0.'
        ]);

        $usuario_pk = session('usuario_pk');
        if (!$usuario_pk) {
            return redirect('/login');
        }

        $efectivo = new Entradas_caja();
        $efectivo->monto_entrada_caja = $validated['monto_entrada_caja'];
        $efectivo->tipo_entrada_caja = 'Inicial';
        $efectivo->concepto_entrada_caja = 'Apertura de caja.';
        $efectivo->fecha_entrada_caja = now();
        $efectivo->usuario_fk = $usuario_pk;
        $efectivo->save();

        return back()->with(
            $efectivo->exists ? 'success' : 'error',
            $efectivo->exists ? 'Registro exitoso' : 'Error al registrar'
        );
    }
}
