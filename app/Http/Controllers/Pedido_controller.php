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
use App\Models\Ingrediente;
use App\Models\Tipo_ingrediente;
use App\Models\Inventario;
use App\Models\Entradas_caja;
use App\Models\Detalle_pedido_ingrediente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pedido_controller extends Controller
{
    public function mostrarParaInsertar(){
        $clientes = Cliente::all();
        $empleados = Empleado::where('estatus_empleado', '=', 1)->get();
        $mediosPedido = Medio_pedido::where('estatus_medio_pedido', '=', 1)->get();
        $tiposPago = Tipo_pago::where('estatus_tipo_pago', '=', 1)->get();
        $productos = Producto::where('estatus_producto', '=', 1)
            ->orderByDesc('nombre_producto')
            ->get();

        $palabrasClaveToppings = ['topping', 'extra', 'aderezo', 'salsa', 'condimento', 'verdura', 'carne fria'];
        $tiposDeIngrediente = Tipo_ingrediente::where('estatus_tipo_ingrediente', '=', 1)->get();
        $toppingTipoIds = [];
        foreach ($tiposDeIngrediente as $tipo) {
            foreach ($palabrasClaveToppings as $keyword) {
                // Uso de stripos para una búsqueda que no distingue mayúsculas/minúsculas
                if (stripos($tipo->nombre_tipo_ingrediente, $keyword) !== false) {
                    $toppingTipoIds[] = $tipo->tipo_ingrediente_pk;
                    break; // Si encuentra una coincidencia, pasamos al siguiente tipo
                }
            }
        }
        $datosIngrediente = Ingrediente::where('estatus_ingrediente', '=', 1)
            ->whereIn('tipo_ingrediente_fk', $toppingTipoIds)
            ->get();

        $USUARIO_PK = session('usuario_pk');

        if (!$USUARIO_PK) {
            return redirect('/login');
        }

        // Evaluación de si ya hay entrada inicial de caja hoy
        $registroHoy = Entradas_caja::whereDate('fecha_entrada_caja', Carbon::today()->toDateString())
            ->where('tipo_entrada_caja', 'Inicial')
            ->exists();
        $hoy = Carbon::today()->toDateString();

        return view('inicio', compact(
            'clientes',
            'empleados',
            'mediosPedido',
            'tiposPago',
            'productos',
            'datosIngrediente',
            'registroHoy',
            'hoy'
        ));
    }

    public function insertar(Request $req) {
        // ====================================================================
        // 1. VALIDACIÓN DE DATOS DE ENTRADA
        // ====================================================================
        $req->validate([
            'cliente_fk' => 'nullable|integer|exists:cliente,cliente_pk',
            'fecha_hora_pedido' => 'required|date',
            'medio_pedido_fk' => 'required|integer|exists:medio_pedido,medio_pedido_pk',
            'tipo_pago_fk' => 'required|integer|exists:tipo_pago,tipo_pago_pk',
            'monto_total' => 'required|numeric|min:0',
            'pago' => 'required|numeric|min:0',
            'cambio' => 'required|numeric',
            'numero_transaccion' => 'nullable|string|max:50',
            'notas_remision' => 'nullable|string|max:255',
            'productos' => 'required|array|min:1',
            'productos.*.cantidad_producto' => 'required|integer|min:1',
            'productos.*.nombre_producto' => 'required|string|max:100',
            'productos.*.ingredientes_personalizados' => 'nullable|array',
            'productos.*.ingredientes_personalizados.*' => 'numeric|min:0', // La proporción
        ]);

        $productos_faltantes = [];
        $pedido_creado = null;

        // ====================================================================
        // 2. INICIO DE LA TRANSACCIÓN
        // ====================================================================
        try {
            $pedido_creado = DB::transaction(function () use ($req) {
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
                $pedido->save();

                $tipos_palabras = [
                    'bebida' => ['bebida', 'refresco', 'licor', 'agua', 'cafe', 'té', 'jugo'],
                    'postre' => ['postre', 'pastel', 'gelatina', 'pay'],
                    'aderezo' => ['aderezo', 'salsa', 'condimento'],
                ];

                foreach ($req->productos as $producto_fk => $detalle) {
                    $detallePedido = new Detalle_pedido();
                    $detallePedido->pedido_fk = $pedido->pedido_pk;
                    $detallePedido->producto_fk = $producto_fk;
                    $detallePedido->cantidad_producto = $detalle['cantidad_producto'];
                    $detallePedido->save();

                    $producto = Producto::find($producto_fk);
                    if (!$producto) { 
                        $nombreProductoFallido = $detalle['nombre_producto'] ?? "ID: {$producto_fk}";
                        throw new \Exception("El producto '{$nombreProductoFallido} no se encuentra disponible.");
                    }

                    $tipo_detectado = 'pizza';
                    foreach ($tipos_palabras as $tipo => $palabras) {
                        foreach ($palabras as $palabra) {
                            // Uso de stripos para una búsqueda que no distingue mayúsculas/minúsculas
                            if (stripos($producto->tipo_producto->nombre_tipo_producto, $palabra) !== false) {
                                $tipo_detectado = $tipo;
                                break 2;
                            }
                        }
                    }

                    switch ($tipo_detectado) {
                        case 'bebida':
                        case 'postre':
                        case 'aderezo':
                            $this->descontarDeInventarioPorProducto($producto_fk, $detalle['cantidad_producto'], $productos_faltantes);
                            break;

                        default:
                            $this->descontarIngredientesDeProducto($producto_fk, $detalle['cantidad_producto'], $productos_faltantes);

                            if (isset($detalle['ingredientes_personalizados']) && is_array($detalle['ingredientes_personalizados'])) {
                                foreach ($detalle['ingredientes_personalizados'] as $ingrediente_pk => $proporcion) {
                                    $dpi = new Detalle_pedido_ingrediente();
                                    $dpi->detalle_pedido_fk = $detallePedido->detalle_pedido_pk;
                                    $dpi->ingrediente_fk = $ingrediente_pk;
                                    $dpi->cantidad_usada = $proporcion * $detalle['cantidad_producto'];
                                    $dpi->save();

                                    $this->descontarIngrediente($ingrediente_pk, $dpi->cantidad_usada, $productos_faltantes);
                                }
                            }
                            break;
                    }
                }

                return $pedido;
            });
        } catch (\Throwable $e) {
            // Errores reales (ej. producto no encontrado)
            // La transacción se revierte automáticamente y no guarda nada
            return redirect()->back()
                ->with('registro_error', 'Hubo un problema inesperado y el pedido no se guardó. Error: ' . $e->getMessage())
                ->withInput();
        }

        // El pedido se guardó, pero se notifica la falta de stock
        if (!empty($productos_faltantes)) {
            $listaUnicaFaltantes = array_unique($productos_faltantes);
            $mensaje = 'Pedido registrado, pero faltan estos ingredientes/productos: ' . implode(', ', $listaUnicaFaltantes);

            return redirect('/')
                ->with('falta_stock', $mensaje)
                ->with('pedido_pk', $pedido_creado->pedido_pk);
        }

        // Mensaje de éxito
        return redirect('/')
            ->with('pedido_exitoso', 'Pedido registrado con éxito.')
            ->with('pedido_pk', $pedido_creado->pedido_pk);
    }

    public function mostrarTicket($pedido_pk){
        $pedido = Pedido::with(
            'cliente', 
            'productos.tipo_producto', 
            'productos.ingredientesPersonalizados.ingrediente', 
            'empleado.usuario', 
            'medio_pedido', 
            'tipo_pago'
        )->findOrFail($pedido_pk);
        return view('ticket', compact('pedido'));
    }

    public function mostrar(){
        $USUARIO_PK = session('usuario_pk');
        if (!$USUARIO_PK) {
            return redirect('/login');
        }

        $datosPedido = Pedido::with([
            'detalle_pedido.producto.tipo_producto',
            'detalle_pedido.ingredientesPersonalizados.ingrediente'
        ])->get();
        $datosTipo_pago = Tipo_pago::all();
        $datosMedio_pedido = Medio_pedido::all();

        return view('ventas', compact('datosPedido', 'datosTipo_pago', 'datosMedio_pedido'));
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
        $datosTipo_pago = Tipo_pago::all();
        $datosMedio_pedido = Medio_pedido::all();

        return view('ventas', compact('datosPedido', 'datosTipo_pago', 'datosMedio_pedido'));
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
                $productos = Producto::where('estatus_producto', '=', 1)
                    ->orderByDesc('nombre_producto')
                    ->get();

                $palabrasClaveToppings = ['topping', 'extra', 'aderezo', 'salsa', 'condimento', 'verdura', 'carne fria'];
                $tiposDeIngrediente = Tipo_ingrediente::where('estatus_tipo_ingrediente', '=', 1)->get();
                $toppingTipoIds = [];
                foreach ($tiposDeIngrediente as $tipo) {
                    foreach ($palabrasClaveToppings as $keyword) {
                        // Uso de stripos para una búsqueda que no distingue mayúsculas/minúsculas
                        if (stripos($tipo->nombre_tipo_ingrediente, $keyword) !== false) {
                            $toppingTipoIds[] = $tipo->tipo_ingrediente_pk;
                            break; // Si encuentra una coincidencia, pasamos al siguiente tipo
                        }
                    }
                }
                $datosIngrediente = Ingrediente::where('estatus_ingrediente', '=', 1)
                    ->whereIn('tipo_ingrediente_fk', $toppingTipoIds)
                    ->get();

                return view('editarPedido', compact('datosPedido', 'clientes', 'mediosPedido', 'tiposPago', 'productos', 'datosIngrediente'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
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

    public function actualizar(Request $req, $pedido_pk) {
        // ====================================================================
        // 1. VALIDACIÓN DE DATOS DE ENTRADA
        // ====================================================================
        $req->validate([
            'cliente_fk' => 'nullable|integer|exists:cliente,cliente_pk',
            'fecha_hora_pedido' => 'required|date',
            'medio_pedido_fk' => 'required|integer|exists:medio_pedido,medio_pedido_pk',
            'tipo_pago_fk' => 'required|integer|exists:tipo_pago,tipo_pago_pk',
            'monto_total' => 'required|numeric|min:0',
            'pago' => 'required|numeric|min:0',
            'cambio' => 'required|numeric',
            'numero_transaccion' => 'nullable|string|max:50',
            'notas_remision' => 'nullable|string|max:255',
            'productos' => 'required|array|min:1',
            'productos.*.cantidad_producto' => 'required|integer|min:1',
            'productos.*.nombre_producto' => 'required|string|max:100',
            'productos.*.ingredientes_personalizados' => 'nullable|array',
            'productos.*.ingredientes_personalizados.*' => 'numeric|min:0',
        ]);

        // ====================================================================
        // 2. INICIO DE LA TRANSACCIÓN ATÓMICA
        // ====================================================================
        try {
            DB::transaction(function () use ($req, $pedido_pk) {
                
                // 2.1. OBTENER EL PEDIDO ORIGINAL COMPLETO
                $pedidoOriginal = Pedido::with([
                    'detalle_pedido.producto.tipo_producto', 
                    'detalle_pedido.ingredientesPersonalizados'
                ])->findOrFail($pedido_pk);

                // 2.2. REVERTIR EL STOCK DEL PEDIDO ORIGINAL
                $this->revertirInventarioCompleto($pedidoOriginal);

                // 2.3. ELIMINAR DETALLES ANTIGUOS
                // Primero los hijos (ingredientes personalizados) y luego los padres (detalles del pedido)
                $detalleIds = $pedidoOriginal->detalle_pedido->pluck('detalle_pedido_pk');
                Detalle_pedido_ingrediente::whereIn('detalle_pedido_fk', $detalleIds)->delete();
                Detalle_pedido::where('pedido_fk', $pedidoOriginal->pedido_pk)->delete();

                // 2.4. ACTUALIZAR LOS DATOS DEL PEDIDO PRINCIPAL
                $pedidoOriginal = Pedido::findOrFail($pedido_pk);

                // Obtenemos todos los datos del request.
                $datosParaActualizar = $req->all();
                
                // Forzamos el recálculo del cambio en el servidor.
                $montoTotal = (float) $datosParaActualizar['monto_total'];
                $pago = (float) $datosParaActualizar['pago'];
                $datosParaActualizar['cambio'] = $pago >= $montoTotal ? $pago - $montoTotal : 0;

                // Usamos el array modificado para actualizar el modelo.
                $pedidoOriginal->fill($datosParaActualizar);
                $pedidoOriginal->save();

                // 2.5. INSERTAR NUEVOS DETALLES Y DESCONTAR NUEVO STOCK
                $productos_faltantes = [];
                $this->procesarYDescontarProductos($req, $pedidoOriginal, $productos_faltantes);

                // 2.6. VERIFICAR STOCK Y ANULAR SI ES NECESARIO
                if (!empty($productos_faltantes)) {
                    $listaUnicaFaltantes = array_unique($productos_faltantes);
                    throw new \Exception('Stock insuficiente para los nuevos productos: ' . implode(', ', $listaUnicaFaltantes));
                }
            });

        } catch (\Throwable $e) {
            // Si algo falla (producto no encontrado, falta de stock, etc.), la transacción se anula.
            return back()
                ->with('error', 'Error al actualizar el pedido: ' . $e->getMessage())
                ->withInput();
        }
        
        // ====================================================================
        // 3. RESPUESTA DE ÉXITO
        // ====================================================================
        return redirect('/ventas')
            ->with('success', 'Pedido actualizado correctamente.')
            ->with('pedido_pk', $pedido_pk);
    }

    private function revertirInventarioCompleto($pedido) {
        $tipos_palabras = [
            'bebida' => ['bebida', 'refresco', 'licor', 'agua', 'cafe', 'té', 'jugo'],
            'postre' => ['postre', 'pastel', 'gelatina', 'pay'],
            'aderezo' => ['aderezo', 'salsa', 'condimento'],
        ];

        foreach ($pedido->detalle_pedido as $detalle) {
            $producto = $detalle->producto;
            $cantidad = $detalle->cantidad_producto;
            $tipo_detectado = $this->detectarTipoProducto($producto, $tipos_palabras);

            if ($tipo_detectado !== 'pizza') {
                // Reintegrar stock de productos comprados (bebidas, postres, etc.)
                $this->reintegrarStockPorProducto($producto->producto_pk, $cantidad);
            } else {
                // Reintegrar stock de productos fabricados (pizzas)
                // a) Revertir ingredientes base
                $ingredientesBase = Detalle_ingrediente::where('producto_fk', $producto->producto_pk)->get();
                foreach ($ingredientesBase as $ingrediente) {
                    $this->reintegrarStockPorIngrediente($ingrediente->ingrediente_fk, $ingrediente->cantidad_necesaria * $cantidad);
                }

                // b) Revertir ingredientes personalizados
                foreach ($detalle->ingredientesPersonalizados as $ingredienteExtra) {
                    $this->reintegrarStockPorIngrediente($ingredienteExtra->ingrediente_fk, $ingredienteExtra->cantidad_usada);
                }
            }
        }
    }

    private function procesarYDescontarProductos(Request $req, Pedido $pedido, &$productos_faltantes) {
        $tipos_palabras = [
            'bebida' => ['bebida', 'refresco', 'licor', 'agua', 'cafe', 'té', 'jugo'],
            'postre' => ['postre', 'pastel', 'gelatina', 'pay'],
            'aderezo' => ['aderezo', 'salsa', 'condimento'],
        ];

        foreach ($req->productos as $producto_fk => $detalle) {
            $detallePedido = new Detalle_pedido([
                'pedido_fk' => $pedido->pedido_pk,
                'producto_fk' => $producto_fk,
                'cantidad_producto' => $detalle['cantidad_producto'],
            ]);
            $detallePedido->save();

            $producto = Producto::with('tipo_producto')->find($producto_fk);
            if (!$producto) {
                $nombreProductoFallido = $detalle['nombre_producto'] ?? "ID: {$producto_fk}";
                throw new \Exception("El producto '{$nombreProductoFallido}' no se encuentra en la base de datos.");
            }

            $tipo_detectado = $this->detectarTipoProducto($producto, $tipos_palabras);

            if ($tipo_detectado !== 'pizza') {
                $this->descontarDeInventarioPorProducto($producto_fk, $detalle['cantidad_producto'], $productos_faltantes);
            } else {
                // Descontar ingredientes base de la pizza
                $this->descontarIngredientesDeProducto($producto_fk, $detalle['cantidad_producto'], $productos_faltantes);

                // Descontar ingredientes personalizados (extras)
                if (isset($detalle['ingredientes_personalizados']) && is_array($detalle['ingredientes_personalizados'])) {
                    foreach ($detalle['ingredientes_personalizados'] as $ingrediente_pk => $proporcion) {
                        $cantidad_usada = $proporcion * $detalle['cantidad_producto'];
                        
                        Detalle_pedido_ingrediente::create([
                            'detalle_pedido_fk' => $detallePedido->detalle_pedido_pk,
                            'ingrediente_fk' => $ingrediente_pk,
                            'cantidad_usada' => $cantidad_usada,
                        ]);

                        $this->descontarIngrediente($ingrediente_pk, $cantidad_usada, $productos_faltantes);
                    }
                }
            }
        }
    }

    private function reintegrarStockPorIngrediente($ingrediente_fk, $cantidad_devolver) {
        $inventario = Inventario::where('ingrediente_fk', $ingrediente_fk)->first();
        if ($inventario) {
            // Lógica para devolver unidades y re-calcular paquetes si es necesario.
            // Asumo que tu lógica original aquí es correcta.
            $inventario->cantidad_parcial += $cantidad_devolver;
            if ($inventario->cantidad_paquete > 0 && $inventario->cantidad_parcial >= $inventario->cantidad_paquete) {
                $paquetes_adicionales = floor($inventario->cantidad_parcial / $inventario->cantidad_paquete);
                $inventario->cantidad_inventario += $paquetes_adicionales;
                $inventario->cantidad_parcial %= $inventario->cantidad_paquete;
            }
            $inventario->save();
        }
    }

    private function reintegrarStockPorProducto($producto_fk, $cantidad_devolver) {
        $inventario = Inventario::where('producto_fk', $producto_fk)->first();
        if ($inventario) {
            // Lógica para devolver unidades y re-calcular paquetes si es necesario.
            // Asumo que tu lógica original aquí es correcta.
            $inventario->cantidad_parcial += $cantidad_devolver;
            if ($inventario->cantidad_paquete > 0 && $inventario->cantidad_parcial >= $inventario->cantidad_paquete) {
                $paquetes_adicionales = floor($inventario->cantidad_parcial / $inventario->cantidad_paquete);
                $inventario->cantidad_inventario += $paquetes_adicionales;
                $inventario->cantidad_parcial %= $inventario->cantidad_paquete;
            }
            $inventario->save();
        }
    }

    private function detectarTipoProducto($producto, $tipos_palabras) {
        if (!$producto || !$producto->tipo_producto) return 'pizza'; // Valor por defecto

        $nombreTipo = $producto->tipo_producto->nombre_tipo_producto;
        foreach ($tipos_palabras as $tipo => $palabras) {
            foreach ($palabras as $palabra) {
                if (stripos($nombreTipo, $palabra) !== false) {
                    return $tipo;
                }
            }
        }
        return 'pizza';
    }

    private function descontarIngredientesDeProducto($producto_fk, $cantidad_producto, &$productos_faltantes){
        $ingredientes = Detalle_ingrediente::where('producto_fk', $producto_fk)->get();

        foreach ($ingredientes as $ingrediente) {
            $ingrediente_fk = $ingrediente->ingrediente_fk;
            $cantidad_necesaria = $ingrediente->cantidad_necesaria * $cantidad_producto;
            $this->descontarIngrediente($ingrediente_fk, $cantidad_necesaria, $productos_faltantes);
        }
    }

    private function descontarIngrediente($ingrediente_fk, $cantidad_necesaria, &$productos_faltantes){
        $inventario = Inventario::where('ingrediente_fk', $ingrediente_fk)->first();

        if (!$inventario) {
            $productos_faltantes[] = 'Ingrediente ID: ' . $ingrediente_fk;
            return;
        }

        if ($inventario->cantidad_parcial >= $cantidad_necesaria) {
            $inventario->cantidad_parcial -= $cantidad_necesaria;
        } else {
            $cantidad_necesaria -= $inventario->cantidad_parcial;
            $inventario->cantidad_parcial = 0;
            $paquetes_necesarios = ceil($cantidad_necesaria / $inventario->cantidad_paquete);
            $inventario->cantidad_inventario -= $paquetes_necesarios;
            $sobrante = ($paquetes_necesarios * $inventario->cantidad_paquete) - $cantidad_necesaria;
            $inventario->cantidad_parcial = $sobrante;
        }

        if ($inventario->cantidad_inventario < 0 || $inventario->cantidad_parcial < 0) {
            $productos_faltantes[] = $inventario->ingrediente->nombre_ingrediente ?? 'Ingrediente desconocido';
        }

        $inventario->save();
    }

    private function descontarDeInventarioPorProducto($producto_fk, $cantidad_producto, &$productos_faltantes){
        $inventario = Inventario::where('producto_fk', $producto_fk)->first();

        if (!$inventario) {
            $productos_faltantes[] = 'Producto ID: ' . $producto_fk;
            return;
        }

        if ($inventario->cantidad_parcial >= $cantidad_producto) {
            $inventario->cantidad_parcial -= $cantidad_producto;
        } else {
            $cantidad_producto -= $inventario->cantidad_parcial;
            $inventario->cantidad_parcial = 0;
            $paquetes_necesarios = ceil($cantidad_producto / $inventario->cantidad_paquete);
            $inventario->cantidad_inventario -= $paquetes_necesarios;
            $sobrante = ($paquetes_necesarios * $inventario->cantidad_paquete) - $cantidad_producto;
            $inventario->cantidad_parcial = $sobrante;
        }

        if ($inventario->cantidad_inventario < 0 || $inventario->cantidad_parcial < 0) {
            $productos_faltantes[] = $inventario->producto->nombre_producto ?? 'Producto desconocido';
        }

        $inventario->save();
    }
}
