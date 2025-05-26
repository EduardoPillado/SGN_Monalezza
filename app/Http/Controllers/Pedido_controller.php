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
            return view('inicio', compact('clientes', 'empleados', 'mediosPedido', 'tiposPago', 'productos'));
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
            $productos_faltantes = [];  // Array para acumular los productos o ingredientes faltantes
            
            if (!empty($req->productos) && is_array($req->productos)) {
                foreach ($req->productos as $producto_fk => $detalle) {
                    if (isset($detalle['cantidad_producto'])) {
                        // Registrar el detalle del pedido
                        $detallePedido = new Detalle_pedido();
                        $detallePedido->pedido_fk = $pedido->pedido_pk;
                        $detallePedido->producto_fk = $producto_fk;
                        $detallePedido->cantidad_producto = $detalle['cantidad_producto'];
                        $detallePedido->save();
        
                        // Verificar el tipo de producto
                        $producto = Producto::find($producto_fk);
                        if (!$producto) {
                            return redirect()->back()->with('registro_error', 'Producto no encontrado.');
                        }
        
                        if ($producto->tipo_producto_fk === 6) {
                            // Procesar bebidas: restar del inventario directo
                            $inventario = Inventario::where('producto_fk', $producto_fk)->first();
        
                            if ($inventario) {
                                $cantidad_requerida = $detalle['cantidad_producto'];
                            
                                // Restar de las unidades parciales primero
                                if ($inventario->cantidad_parcial >= $cantidad_requerida) {
                                    $inventario->cantidad_parcial -= $cantidad_requerida;
                                } else {
                                    // Calcular el sobrante necesario después de usar las parciales
                                    $cantidad_requerida -= $inventario->cantidad_parcial;
                                    $inventario->cantidad_parcial = 0;
                            
                                    // Calcular paquetes necesarios
                                    $paquetes_necesarios = ceil($cantidad_requerida / $inventario->cantidad_paquete);
                            
                                    // Restar los paquetes completos disponibles, permitiendo negativos
                                    $inventario->cantidad_inventario -= $paquetes_necesarios;
                            
                                    // Ajustar la cantidad parcial con el sobrante negativo
                                    $sobrante = ($paquetes_necesarios * $inventario->cantidad_paquete) - $cantidad_requerida;
                                    $inventario->cantidad_parcial = $sobrante; // Este puede ser negativo
                                }
                            
                                // **Verificar si quedó en negativo y añadir a productos faltantes**
                                if ($inventario->cantidad_inventario < 0 || $inventario->cantidad_parcial < 0) {
                                    $productos_faltantes[] = $producto->nombre_producto;
                                }
                            
                                $inventario->save();
                            } else {
                                return redirect()->back()->with('registro_error', 'La bebida no está registrada en el inventario.');
                            }
                        } else {
                            // Procesar pizzas: restar ingredientes
                            $ingredientes = Detalle_ingrediente::where('producto_fk', $producto_fk)->get();
        
                            foreach ($ingredientes as $ingrediente) {
                                $ingrediente_fk = $ingrediente->ingrediente_fk;
                                $cantidad_necesaria = $ingrediente->cantidad_necesaria * $detalle['cantidad_producto'];
        
                                $inventario = Inventario::where('ingrediente_fk', $ingrediente_fk)->first();
        
                                if ($inventario) {
                                    if ($inventario->cantidad_parcial >= $cantidad_necesaria) {
                                        $inventario->cantidad_parcial -= $cantidad_necesaria;
                                    } else {
                                        // Calcular el sobrante necesario después de usar las parciales
                                        $cantidad_necesaria -= $inventario->cantidad_parcial;
                                        $inventario->cantidad_parcial = 0;
                                
                                        // Calcular paquetes necesarios
                                        $paquetes_necesarios = ceil($cantidad_necesaria / $inventario->cantidad_paquete);
                                
                                        // Restar los paquetes completos disponibles, permitiendo negativos
                                        $inventario->cantidad_inventario -= $paquetes_necesarios;
                                
                                        // Ajustar la cantidad parcial con el sobrante negativo
                                        $sobrante = ($paquetes_necesarios * $inventario->cantidad_paquete) - $cantidad_necesaria;
                                        $inventario->cantidad_parcial = $sobrante; // Este puede ser negativo
                                    }
                                
                                    // **Verificar si quedó en negativo y añadir a productos faltantes**
                                    if ($inventario->cantidad_inventario < 0 || $inventario->cantidad_parcial < 0) {
                                        $productos_faltantes[] = $ingrediente->ingrediente->nombre_ingrediente;
                                    }
                                
                                    $inventario->save();
                                } else {
                                    return redirect()->back()->with('registro_error', 'El ingrediente no está registrado en el inventario.');
                                }
                            }
                        }
                    } else {
                        return redirect()->back()->with('registro_error', 'Información incompleta de los productos seleccionados.');
                    }
                }
    
                if (!empty($productos_faltantes)) {
                    return redirect('/')
                        ->with('falta_stock', 'Faltan estos productos o ingredientes: ' . implode(', ', $productos_faltantes))
                        ->with('pedido_pk', $pedido->pedido_pk);
                } else {
                    return redirect('/')
                        ->with('pedido_exitoso', 'Pedido registrado con éxito.')
                        ->with('pedido_pk', $pedido->pedido_pk);
                }
            } else {
                return redirect()->back()->with('registro_error', 'No se seleccionaron productos válidos para el pedido.');
            }
        } else {
            return redirect()->back()->with('registro_error', 'Hubo un problema al registrar el pedido.');
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
            return view('ventas', compact('datosPedido'));
        } else {
            return redirect('/login');
        }
    }

    public function pendiente($pedido_pk){
        $datosPedido = Pedido::findOrFail($pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosPedido) {

                $datosPedido->estatus_pedido = 1;
                $datosPedido->save();

                return back()->with('success', 'Cancelación deshecha');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function entregado($pedido_pk){
        $datosPedido = Pedido::findOrFail($pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosPedido) {

                $datosPedido->estatus_pedido = 0;
                $datosPedido->save();

                return back()->with('success', 'Pedido entregado');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function cancelado($pedido_pk){
        $datosPedido = Pedido::findOrFail($pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosPedido) {

                $datosPedido->estatus_pedido = 2;
                $datosPedido->save();

                return back()->with('success', 'Pedido cancelado');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}
