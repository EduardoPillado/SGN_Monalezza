<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Detalle_pedido;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Medio_pedido;
use App\Models\Tipo_pago;
use App\Models\Producto;

class Pedido_controller extends Controller
{
    public function mostrarParaInsertar(){
        $clientes=Cliente::all();
        $empleados=Empleado::where('estatus_empleado', '=', 1)->get();
        $mediosPedido=Medio_pedido::where('estatus_medio_pedido', '=', 1)->get();
        $tiposPago=Tipo_pago::where('estatus_tipo_pago', '=', 1)->get();
        $productos=Producto::where('estatus_producto', '=', 1)->get();

        return view('inicio', compact('clientes', 'empleados', 'mediosPedido', 'tiposPago', 'productos'));
    }

    public function insertar(Request $req) {
        $pedido = new Pedido();
        $pedido->cliente_fk = $req->cliente_fk;
        $USUARIO_PK = session('usuario_pk');
        $pedido->empleado_fk = $USUARIO_PK;
        // $pedido->empleado_fk = $req->empleado_fk;
        $pedido->fecha_hora_pedido = $req->fecha_hora_pedido;
        $pedido->medio_pedido_fk = $req->medio_pedido_fk;
        $pedido->monto_total = $req->monto_total;
        $pedido->numero_transaccion = $req->numero_transaccion;
        $pedido->tipo_pago_fk = $req->tipo_pago_fk;
        $pedido->notas_remision = $req->notas_remision;
        $pedido->estatus_pedido = 1;
    
        if ($pedido->save()) {
            if (!empty($req->productos) && is_array($req->productos)) {
                foreach ($req->productos as $producto_fk => $detalle) {
                    if (isset($detalle['cantidad_producto'])) {
                        $detallePedido = new Detalle_pedido();
                        $detallePedido->pedido_fk = $pedido->pedido_pk;
                        $detallePedido->producto_fk = $producto_fk;
                        $detallePedido->cantidad_producto = $detalle['cantidad_producto'];
                        $detallePedido->save();
                    } else {
                        return back()->with('error', 'Información incompleta de los productos seleccionados');
                    }
                }
                return back()->with('success', 'Pedido registrado');
            } else {
                return back()->with('error', 'No se seleccionaron productos válidos para el pedido');
            }
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
    
    public function mostrar(){
        $datosPedido = Pedido::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('ventas', compact('datosPedido'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function pendiente($pedido_pk){
        $datosPedido = Pedido::findOrFail($pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosPedido) {

                    $datosPedido->estatus_pedido = 1;
                    $datosPedido->save();

                    return back()->with('success', 'Pedido cancelado');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function entregado($pedido_pk){
        $datosPedido = Pedido::findOrFail($pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosPedido) {

                    $datosPedido->estatus_pedido = 0;
                    $datosPedido->save();

                    return back()->with('success', 'Pedido entregado');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function cancelado($pedido_pk){
        $datosPedido = Pedido::findOrFail($pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosPedido) {

                    $datosPedido->estatus_pedido = 2;
                    $datosPedido->save();

                    return back()->with('success', 'Pedido cancelado');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }
}
