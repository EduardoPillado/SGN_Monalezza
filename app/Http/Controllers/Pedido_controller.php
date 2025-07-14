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
use Illuminate\Support\Facades\DB;

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

    public function filtrar(Request $req){
        $fecha = $req->input('fecha');
        $estatus = $req->input('estatus');

        $query = Pedido::with('detalle_pedido.producto.tipo_producto');

        // Por fecha específica
        if ($fecha) {
            $query->whereDate('fecha_hora_pedido', $fecha);
        }

        // Por estatus de pedido (entregado, pendiente, cancelado)
        if (in_array($estatus, ['0', '1', '2'])) {
            $query->where('estatus_pedido', $estatus);
        }

        $datosPedido = $query->get();

        return view('ventas', compact('datosPedido'));
    }

    public function datosParaEdicion($pedido_pk){
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                $datosPedido = Pedido::with('detalle_pedido.producto')->findOrFail($pedido_pk);
                $clientes = Cliente::all();
                $empleados=Empleado::where('estatus_empleado', '=', 1)->get();
                $mediosPedido = Medio_pedido::where('estatus_medio_pedido', '=', 1)->get();
                $tiposPago = Tipo_pago::where('estatus_tipo_pago', '=', 1)->get();
                $productos = Producto::where('estatus_producto', '=', 1)->with('tipo_producto')->get();

                return view('editarPedido', compact('datosPedido', 'clientes', 'mediosPedido', 'tiposPago', 'productos'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $pedido_pk){
        DB::beginTransaction();
        try {
            $datosPedido = Pedido::findOrFail($pedido_pk);

            // Revertir los productos e ingredientes al inventario antes de cambiar algo
            foreach ($datosPedido->detalle_pedido as $detalle) {
                $producto = $detalle->producto;
                $cantidad = $detalle->cantidad_producto;

                if ($producto->tipo_producto_fk === 6) {
                    // Es bebida
                    $inventario = Inventario::where('producto_fk', $producto->producto_pk)->first();
                    if ($inventario) {
                        $inventario->cantidad_parcial += $cantidad;
                        if ($inventario->cantidad_parcial >= $inventario->cantidad_paquete) {
                            $paquetes_adicionales = floor($inventario->cantidad_parcial / $inventario->cantidad_paquete);
                            $inventario->cantidad_inventario += $paquetes_adicionales;
                            $inventario->cantidad_parcial %= $inventario->cantidad_paquete;
                        }
                        $inventario->save();
                    }
                } else {
                    // Es pizza u otro con ingredientes
                    $ingredientes = Detalle_ingrediente::where('producto_fk', $producto->producto_pk)->get();
                    foreach ($ingredientes as $ingrediente) {
                        $inventario = Inventario::where('ingrediente_fk', $ingrediente->ingrediente_fk)->first();
                        if ($inventario) {
                            $cantidad_devolver = $ingrediente->cantidad_necesaria * $cantidad;
                            $inventario->cantidad_parcial += $cantidad_devolver;
                            if ($inventario->cantidad_parcial >= $inventario->cantidad_paquete) {
                                $paquetes = floor($inventario->cantidad_parcial / $inventario->cantidad_paquete);
                                $inventario->cantidad_inventario += $paquetes;
                                $inventario->cantidad_parcial %= $inventario->cantidad_paquete;
                            }
                            $inventario->save();
                        }
                    }
                }
            }

            // Eliminar los detalles anteriores
            Detalle_pedido::where('pedido_fk', $datosPedido->pedido_pk)->delete();

            // Actualizar campos principales
            $datosPedido->cliente_fk = $req->cliente_fk;
            $datosPedido->fecha_hora_pedido = $req->fecha_hora_pedido;
            $datosPedido->medio_pedido_fk = $req->medio_pedido_fk;
            $datosPedido->monto_total = $req->monto_total;
            $datosPedido->numero_transaccion = $req->numero_transaccion;
            $datosPedido->tipo_pago_fk = $req->tipo_pago_fk;
            $datosPedido->notas_remision = $req->notas_remision;
            $datosPedido->pago = $req->pago;
            $datosPedido->cambio = $req->cambio;
            $datosPedido->save();

            // Procesar nuevos productos igual que en insertar
            foreach ($req->productos as $producto_fk => $detalle) {
                $detallePedido = new Detalle_pedido();
                $detallePedido->pedido_fk = $datosPedido->pedido_pk;
                $detallePedido->producto_fk = $producto_fk;
                $detallePedido->cantidad_producto = $detalle['cantidad_producto'];
                $detallePedido->save();

                $producto = Producto::find($producto_fk);

                if ($producto->tipo_producto_fk === 6) {
                    $inventario = Inventario::where('producto_fk', $producto_fk)->first();
                    $cantidad_requerida = $detalle['cantidad_producto'];
                    if ($inventario) {
                        if ($inventario->cantidad_parcial >= $cantidad_requerida) {
                            $inventario->cantidad_parcial -= $cantidad_requerida;
                        } else {
                            $cantidad_requerida -= $inventario->cantidad_parcial;
                            $inventario->cantidad_parcial = 0;
                            $paquetes_necesarios = ceil($cantidad_requerida / $inventario->cantidad_paquete);
                            $inventario->cantidad_inventario -= $paquetes_necesarios;
                            $inventario->cantidad_parcial = ($paquetes_necesarios * $inventario->cantidad_paquete) - $cantidad_requerida;
                        }
                        $inventario->save();
                    }
                } else {
                    $ingredientes = Detalle_ingrediente::where('producto_fk', $producto_fk)->get();
                    foreach ($ingredientes as $ingrediente) {
                        $inventario = Inventario::where('ingrediente_fk', $ingrediente->ingrediente_fk)->first();
                        $cantidad_necesaria = $ingrediente->cantidad_necesaria * $detalle['cantidad_producto'];
                        if ($inventario) {
                            if ($inventario->cantidad_parcial >= $cantidad_necesaria) {
                                $inventario->cantidad_parcial -= $cantidad_necesaria;
                            } else {
                                $cantidad_necesaria -= $inventario->cantidad_parcial;
                                $inventario->cantidad_parcial = 0;
                                $paquetes_necesarios = ceil($cantidad_necesaria / $inventario->cantidad_paquete);
                                $inventario->cantidad_inventario -= $paquetes_necesarios;
                                $inventario->cantidad_parcial = ($paquetes_necesarios * $inventario->cantidad_paquete) - $cantidad_necesaria;
                            }
                            $inventario->save();
                        }
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Pedido actualizado')->with('pedido_pk', $datosPedido->pedido_pk);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
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
