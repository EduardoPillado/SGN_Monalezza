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
use App\Models\Detalle_ingrediente;
use App\Models\Inventario;

class Pedido_controller extends Controller
{
    public function mostrarParaInsertar(){
        $clientes=Cliente::all();
        $empleados=Empleado::where('estatus_empleado', '=', 1)->get();
        $mediosPedido=Medio_pedido::where('estatus_medio_pedido', '=', 1)->get();
        $tiposPago=Tipo_pago::where('estatus_tipo_pago', '=', 1)->get();
        $productos=Producto::where('estatus_producto', '=', 1)->get();

        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('inicio', compact('clientes', 'empleados', 'mediosPedido', 'tiposPago', 'productos'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function insertar(Request $req) {
        $pedido = new Pedido();
        $pedido->cliente_fk = $req->cliente_fk;
        $pedido->empleado_fk = session('usuario_pk');
        $pedido->fecha_hora_pedido = $req->fecha_hora_pedido;
        $pedido->medio_pedido_fk = $req->medio_pedido_fk;
        $pedido->monto_total = $req->monto_total;
        $pedido->numero_transaccion = $req->numero_transaccion;
        $pedido->tipo_pago_fk = $req->tipo_pago_fk;
        $pedido->notas_remision = $req->notas_remision;
        $pedido->pago = $req->pago;
        $pedido->cambio = $req->cambio;
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
    
                        // Obtener ingredientes necesarios para el producto
                        $ingredientes = Detalle_ingrediente::where('producto_fk', $producto_fk)->get();
                        foreach ($ingredientes as $ingrediente) {
                            $ingrediente_fk = $ingrediente->ingrediente_fk;
                            $cantidad_necesaria = $ingrediente->cantidad_necesaria * $detalle['cantidad_producto'];
    
                            // Consultar el inventario para este ingrediente
                            $inventario = Inventario::where('ingrediente_fk', $ingrediente_fk)->first();
                            if ($inventario) {
                                // Sumar la cantidad necesaria a la cantidad parcial
                                $inventario->cantidad_parcial += $cantidad_necesaria;
    
                                // Verificar si cantidad_parcial excede cantidad_paquete
                                while ($inventario->cantidad_parcial >= $inventario->cantidad_paquete) {
                                    $inventario->cantidad_parcial -= $inventario->cantidad_paquete;
                                    $inventario->cantidad_inventario--; // Descontar un paquete completo
                                }
    
                                $inventario->save();
                            } else {
                                return back()->with('error', 'No hay suficiente inventario para el ingrediente requerido.');
                            }
                        }
                    } else {
                        return back()->with('error', 'Información incompleta de los productos seleccionados.');
                    }
                }
                return redirect()->route('ticket.mostrar', ['pedido_pk' => $pedido->pedido_pk])->with('success', 'Pedido registrado');
            } else {
                return back()->with('error', 'No se seleccionaron productos válidos para el pedido.');
            }
        } else {
            return back()->with('error', 'Hay algún problema con la información.');
        }
    }    

    public function mostrarTicket($pedido_pk){
        $pedido = Pedido::with('cliente', 'productos', 'empleado', 'medio_pedido', 'tipo_pago')
            ->findOrFail($pedido_pk);
        return view('ticket', compact('pedido'));
    }
    
    public function mostrar(){
        $datosPedido = Pedido::with('detalle_pedido.producto.tipo_producto')->get();
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

                    return back()->with('success', 'Cancelación deshecha');
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
