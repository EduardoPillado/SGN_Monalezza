<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class Reporte_controller extends Controller
{
    public function generarReporteConsumoPorVentas(Request $req){
        $req->validate([
            'fecha_reporte_consumo_inicio' => 'required|date',
            'fecha_reporte_consumo_fin' => 'required|date|after_or_equal:fecha_reporte_consumo_inicio',
        ]);

        $inicio = Carbon::parse($req->fecha_reporte_consumo_inicio)->startOfDay();
        $fin = Carbon::parse($req->fecha_reporte_consumo_fin)->endOfDay();

        $reporteData = [];

        // ====================================================================
        // PASO 0: IDENTIFICAR TIPOS DE PRODUCTO INVENTARIABLES
        // ====================================================================
        $tiposPalabrasClave = [
            'bebida' => ['bebida', 'refresco', 'licor', 'agua', 'cafe', 'té', 'jugo'],
            'postre' => ['postre', 'pastel', 'gelatina', 'pay', 'flan', 'brownie'],
            'aderezo' => ['aderezo', 'salsa', 'condimento'],
        ];
        $keywordsInventariables = array_merge(...array_values($tiposPalabrasClave));
        
        $inventoriableTipoProductoIds = [];
        $todosLosTiposProducto = DB::table('tipo_producto')->get();
        foreach ($todosLosTiposProducto as $tipo) {
            foreach ($keywordsInventariables as $keyword) {
                if (stripos($tipo->nombre_tipo_producto, $keyword) !== false) {
                    $inventoriableTipoProductoIds[] = $tipo->tipo_producto_pk;
                    break;
                }
            }
        }
        
        // ====================================================================
        // PARTE A: CONSUMO DE INGREDIENTES
        // ====================================================================
        $consumoIngredientes = DB::table('detalle_pedido')
            ->join('producto', 'detalle_pedido.producto_fk', '=', 'producto.producto_pk')
            ->join('detalle_ingrediente', 'producto.producto_pk', '=', 'detalle_ingrediente.producto_fk')
            ->join('ingrediente', 'detalle_ingrediente.ingrediente_fk', '=', 'ingrediente.ingrediente_pk')
            ->join('pedido', 'detalle_pedido.pedido_fk', '=', 'pedido.pedido_pk')
            ->whereBetween('pedido.fecha_hora_pedido', [$inicio, $fin])
            ->where('pedido.estatus_pedido', 0)
            ->select(
                'ingrediente.ingrediente_pk',
                'ingrediente.nombre_ingrediente',
                DB::raw('SUM(detalle_ingrediente.cantidad_necesaria * detalle_pedido.cantidad_producto) as cantidad_consumida')
            )
            ->groupBy('ingrediente.ingrediente_pk', 'ingrediente.nombre_ingrediente')
            ->get();

        if ($consumoIngredientes->isNotEmpty()) {
            $inventarioData_ing = DB::table('inventario')
                ->whereIn('ingrediente_fk', $consumoIngredientes->pluck('ingrediente_pk'))
                ->select('ingrediente_fk', DB::raw('AVG(precio_proveedor / cantidad_paquete) as precio_unitario_prom'))
                ->groupBy('ingrediente_fk')
                ->get()->keyBy('ingrediente_fk');

            foreach ($consumoIngredientes as $item) {
                $precioUnitario = $inventarioData_ing->get($item->ingrediente_pk)->precio_unitario_prom ?? 0;
                $reporteData[] = (object)[
                    'nombre' => $item->nombre_ingrediente,
                    'tipo' => 'Ingrediente',
                    'cantidad_consumida' => $item->cantidad_consumida,
                    'costo_aproximado' => $item->cantidad_consumida * $precioUnitario,
                ];
            }
        }

        // ====================================================================
        // PARTE B: CONSUMO DE PRODUCTOS INVENTARIABLES
        // ====================================================================
        $consumoProductos = DB::table('detalle_pedido')
            ->join('producto', 'detalle_pedido.producto_fk', '=', 'producto.producto_pk')
            ->join('pedido', 'detalle_pedido.pedido_fk', '=', 'pedido.pedido_pk')
            ->where('pedido.estatus_pedido', 0)
            ->whereIn('producto.tipo_producto_fk', $inventoriableTipoProductoIds)
            ->whereBetween('pedido.fecha_hora_pedido', [$inicio, $fin])
            ->select(
                'producto.producto_pk',
                'producto.nombre_producto',
                DB::raw('SUM(detalle_pedido.cantidad_producto) as cantidad_consumida')
            )
            ->groupBy('producto.producto_pk', 'producto.nombre_producto')
            ->get();

        if ($consumoProductos->isNotEmpty()) {
            $inventarioData_prod = DB::table('inventario')
                ->whereIn('producto_fk', $consumoProductos->pluck('producto_pk'))
                ->select('producto_fk', DB::raw('AVG(precio_proveedor / cantidad_paquete) as precio_unitario_prom'))
                ->groupBy('producto_fk')
                ->get()->keyBy('producto_fk');

            foreach ($consumoProductos as $item) {
                $precioUnitario = $inventarioData_prod->get($item->producto_pk)->precio_unitario_prom ?? 0;
                $reporteData[] = (object)[
                    'nombre' => $item->nombre_producto,
                    'tipo' => 'Producto',
                    'cantidad_consumida' => $item->cantidad_consumida,
                    'costo_aproximado' => $item->cantidad_consumida * $precioUnitario,
                ];
            }
        }

        // ====================================================================
        // PARTE C: UNIFICACIÓN Y FINALIZACIÓN
        // ====================================================================
        $consumoDeVentas = collect($reporteData)->sortBy('nombre')->values()->all();
        $costoTotal = collect($consumoDeVentas)->sum('costo_aproximado');

        $datos = [
            'consumoDeVentas' => $consumoDeVentas,
            'costoTotal' => $costoTotal,
            'fecha_inicio' => $inicio->format('d/m/Y'),
            'fecha_fin' => $fin->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('formatoReporteConsumoPorVentas', $datos);
        return $pdf->download('reporte_consumo_por_ventas_' . now()->format('Ymd_His') . '.pdf');
    }







    public function generarReporteInventario(Request $req) {
        $req->validate([
            'fecha_reporte_inventario_inicio' => 'required|date',
            'fecha_reporte_inventario_fin' => 'required|date|after_or_equal:fecha_reporte_inventario_inicio',
        ]);

        $inicio = Carbon::parse($req->fecha_reporte_inventario_inicio)->startOfDay();
        $fin = Carbon::parse($req->fecha_reporte_inventario_fin)->endOfDay();

        // ====================================================================
        // PASO 0: IDENTIFICAR TIPOS DE PRODUCTO INVENTARIABLES (NUEVO)
        // ====================================================================
        $tiposPalabrasClave = [
            'bebida' => ['bebida', 'refresco', 'licor', 'agua', 'cafe', 'té', 'jugo'],
            'postre' => ['postre', 'pastel', 'gelatina', 'pay', 'flan', 'brownie'],
            'aderezo' => ['aderezo', 'salsa', 'condimento'],
            // Puedes agregar más categorías aquí si es necesario
        ];

        $keywordsInventariables = [];
        foreach ($tiposPalabrasClave as $categoria) {
            $keywordsInventariables = array_merge($keywordsInventariables, $categoria);
        }
        
        $inventoriableTipoProductoIds = [];
        $todosLosTiposProducto = DB::table('tipo_producto')->get();

        foreach ($todosLosTiposProducto as $tipo) {
            foreach ($keywordsInventariables as $keyword) {
                // Uso de stripos para una búsqueda case-insensitive (no sensible a mayúsculas/minúsculas)
                if (stripos($tipo->nombre_tipo_producto, $keyword) !== false) {
                    $inventoriableTipoProductoIds[] = $tipo->tipo_producto_pk;
                    break; // Si encuentra una coincidencia, pasa al siguiente tipo de producto
                }
            }
        }

        // ====================================================================
        // PARTE A: CÁLCULO DE MOVIMIENTOS PARA INGREDIENTES
        // ====================================================================

        // Stock Inicial (Ingredientes)
        $stockInicial_ing = DB::table('inventario')
            ->whereNotNull('ingrediente_fk')
            ->where('fecha_inventario', '<', $inicio)
            ->groupBy('ingrediente_fk')
            ->select('ingrediente_fk', DB::raw('SUM(cantidad_inventario * cantidad_paquete + cantidad_parcial) as total'))
            ->get()->keyBy('ingrediente_fk');

        // Entradas (Ingredientes)
        $entradas_ing = DB::table('inventario')
            ->whereNotNull('ingrediente_fk')
            ->whereBetween('fecha_inventario', [$inicio, $fin])
            ->groupBy('ingrediente_fk')
            ->select('ingrediente_fk', DB::raw('SUM(cantidad_inventario * cantidad_paquete + cantidad_parcial) as total'))
            ->get()->keyBy('ingrediente_fk');

        // Salidas por Venta (Ingredientes)
        $salidas_ing = DB::table('detalle_pedido')
            ->join('producto', 'detalle_pedido.producto_fk', '=', 'producto.producto_pk')
            ->join('detalle_ingrediente', 'producto.producto_pk', '=', 'detalle_ingrediente.producto_fk')
            ->join('pedido', 'detalle_pedido.pedido_fk', '=', 'pedido.pedido_pk')
            ->where('pedido.estatus_pedido', 0)
            ->whereBetween('pedido.fecha_hora_pedido', [$inicio, $fin])
            ->groupBy('detalle_ingrediente.ingrediente_fk')
            ->select('detalle_ingrediente.ingrediente_fk', DB::raw('SUM(detalle_ingrediente.cantidad_necesaria * detalle_pedido.cantidad_producto) as total'))
            ->get()->keyBy('ingrediente_fk');

        // Stock Físico Actual (Ingredientes)
        $stockActual_ing = DB::table('inventario')
            ->whereNotNull('ingrediente_fk')
            ->groupBy('ingrediente_fk')
            ->select('ingrediente_fk', DB::raw('SUM(cantidad_inventario * cantidad_paquete + cantidad_parcial) as total'))
            ->get()->keyBy('ingrediente_fk');

        // ====================================================================
        // PARTE B: CÁLCULO DE MOVIMIENTOS PARA PRODUCTOS (CON FILTROS AÑADIDOS)
        // ====================================================================

        // Stock Inicial (Productos Inventariables)
        $stockInicial_prod = DB::table('inventario')
            ->join('producto', 'inventario.producto_fk', '=', 'producto.producto_pk') // JOIN para filtrar por tipo
            ->whereNotNull('inventario.producto_fk')
            ->whereIn('producto.tipo_producto_fk', $inventoriableTipoProductoIds) // FILTRO NUEVO
            ->where('inventario.fecha_inventario', '<', $inicio)
            ->groupBy('inventario.producto_fk')
            ->select('inventario.producto_fk', DB::raw('SUM(inventario.cantidad_inventario * inventario.cantidad_paquete + inventario.cantidad_parcial) as total'))
            ->get()->keyBy('producto_fk');

        // Entradas (Productos Inventariables)
        $entradas_prod = DB::table('inventario')
            ->join('producto', 'inventario.producto_fk', '=', 'producto.producto_pk') // JOIN para filtrar por tipo
            ->whereNotNull('inventario.producto_fk')
            ->whereIn('producto.tipo_producto_fk', $inventoriableTipoProductoIds) // FILTRO NUEVO
            ->whereBetween('inventario.fecha_inventario', [$inicio, $fin])
            ->groupBy('inventario.producto_fk')
            ->select('inventario.producto_fk', DB::raw('SUM(inventario.cantidad_inventario * inventario.cantidad_paquete + inventario.cantidad_parcial) as total'))
            ->get()->keyBy('producto_fk');

        // Salidas por Venta (Productos Inventariables)
        $salidas_prod = DB::table('detalle_pedido')
            ->join('pedido', 'detalle_pedido.pedido_fk', '=', 'pedido.pedido_pk')
            ->join('producto', 'detalle_pedido.producto_fk', '=', 'producto.producto_pk') // JOIN para filtrar por tipo
            ->where('pedido.estatus_pedido', 0)
            ->whereNotNull('detalle_pedido.producto_fk')
            ->whereIn('producto.tipo_producto_fk', $inventoriableTipoProductoIds) // FILTRO NUEVO
            ->whereBetween('pedido.fecha_hora_pedido', [$inicio, $fin])
            ->groupBy('detalle_pedido.producto_fk')
            ->select('detalle_pedido.producto_fk', DB::raw('SUM(detalle_pedido.cantidad_producto) as total'))
            ->get()->keyBy('producto_fk');

        // Stock Físico Actual (Productos Inventariables)
        $stockActual_prod = DB::table('inventario')
            ->join('producto', 'inventario.producto_fk', '=', 'producto.producto_pk') // JOIN para filtrar por tipo
            ->whereNotNull('inventario.producto_fk')
            ->whereIn('producto.tipo_producto_fk', $inventoriableTipoProductoIds) // FILTRO NUEVO
            ->groupBy('inventario.producto_fk')
            ->select('inventario.producto_fk', DB::raw('SUM(inventario.cantidad_inventario * inventario.cantidad_paquete + inventario.cantidad_parcial) as total'))
            ->get()->keyBy('producto_fk');

        // ====================================================================
        // PARTE C: UNIFICACIÓN DE DATOS (CON FILTRO EN LA LISTA DE PRODUCTOS)
        // ====================================================================

        $movimientosInventario = [];

        // Procesar Ingredientes
        $todosLosIngredientes = DB::table('ingrediente')->orderBy('nombre_ingrediente')->get();
        foreach ($todosLosIngredientes as $ingrediente) {
            $id = $ingrediente->ingrediente_pk;
            $stockInicial = $stockInicial_ing->get($id)->total ?? 0;
            $entradas = $entradas_ing->get($id)->total ?? 0;
            $salidas = $salidas_ing->get($id)->total ?? 0;
            $stockActual = $stockActual_ing->get($id)->total ?? 0;

            if ($stockInicial > 0 || $entradas > 0 || $salidas > 0 || $stockActual > 0) {
                $movimientosInventario[] = (object)[
                    'nombre' => $ingrediente->nombre_ingrediente,
                    'tipo' => 'Ingrediente',
                    'stock_inicial' => $stockInicial,
                    'entradas' => $entradas,
                    'salidas_venta' => $salidas,
                    'stock_final_teorico' => $stockInicial + $entradas - $salidas,
                    'stock_fisico_actual' => $stockActual,
                ];
            }
        }

        // Procesar Productos
        $todosLosProductos = DB::table('producto')
            ->whereIn('tipo_producto_fk', $inventoriableTipoProductoIds)
            ->orderBy('nombre_producto')
            ->get();

        foreach ($todosLosProductos as $producto) {
            $id = $producto->producto_pk;
            $stockInicial = $stockInicial_prod->get($id)->total ?? 0;
            $entradas = $entradas_prod->get($id)->total ?? 0;
            $salidas = $salidas_prod->get($id)->total ?? 0;
            $stockActual = $stockActual_prod->get($id)->total ?? 0;

            // Condición para no incluir productos que ni se venden ni están en inventario
            if ($stockInicial > 0 || $entradas > 0 || $salidas > 0 || $stockActual > 0) {
                $movimientosInventario[] = (object)[
                    'nombre' => $producto->nombre_producto,
                    'tipo' => 'Producto',
                    'stock_inicial' => $stockInicial,
                    'entradas' => $entradas,
                    'salidas_venta' => $salidas,
                    'stock_final_teorico' => $stockInicial + $entradas - $salidas,
                    'stock_fisico_actual' => $stockActual,
                ];
            }
        }

        // Ordenar la lista final alfabéticamente por nombre
        $movimientosInventario = collect($movimientosInventario)->sortBy('nombre')->values()->all();

        // Preparación de datos y generación del reporte en PDF
        $datos = [
            'movimientosInventario' => $movimientosInventario,
            'fecha_inicio' => $inicio->format('d/m/Y'),
            'fecha_fin' => $fin->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('formatoReporteInventario', $datos);
        return $pdf->download('reporte_inventario_' . now()->format('Ymd_His') . '.pdf');
    }





    

    public function generarReporteProducto(Request $req) {
        $req->validate([
            'fecha_reporte_producto_inicio' => 'required|date',
            'fecha_reporte_producto_fin' => 'required|date|after_or_equal:fecha_reporte_producto_inicio',
        ]);

        $inicio = Carbon::parse($req->fecha_reporte_producto_inicio)->startOfDay();
        $fin = Carbon::parse($req->fecha_reporte_producto_fin)->endOfDay();

        // Obtener productos vendidos
        $productosVendidos = DB::table('detalle_pedido')
            ->join('producto', 'detalle_pedido.producto_fk', '=', 'producto.producto_pk')
            ->join('tipo_producto', 'producto.tipo_producto_fk', '=', 'tipo_producto.tipo_producto_pk')
            ->join('pedido', 'detalle_pedido.pedido_fk', '=', 'pedido.pedido_pk')
            ->whereBetween('pedido.fecha_hora_pedido', [$inicio, $fin])
            ->where('pedido.estatus_pedido', 0)
            ->select(
                'producto.producto_pk',
                'producto.nombre_producto',
                'tipo_producto.nombre_tipo_producto',
                'producto.precio_producto',
                DB::raw('SUM(detalle_pedido.cantidad_producto) as cantidad_vendida'),
                DB::raw('SUM(producto.precio_producto * detalle_pedido.cantidad_producto) as total_recaudado')
            )
            ->groupBy('producto.producto_pk', 'producto.nombre_producto', 'tipo_producto.nombre_tipo_producto', 'producto.precio_producto')
            ->orderByDesc('cantidad_vendida')
            ->get();

        // Calcular el total vendido para porcentaje
        if ($productosVendidos->isEmpty()) {
            // Si no se vendió nada, redirigir o mostrar un mensaje.
            return back()->with('error', 'No se encontraron productos vendidos en el período seleccionado.');
        }

        $totalVendidas = $productosVendidos->sum('cantidad_vendida');
        $productosIds = $productosVendidos->pluck('producto_pk')->toArray();

        // A. Costo de productos FABRICADOS (a partir de sus ingredientes)
        $costosFabricados = DB::table('detalle_ingrediente')
            ->join('inventario', 'detalle_ingrediente.ingrediente_fk', '=', 'inventario.ingrediente_fk')
            ->whereIn('detalle_ingrediente.producto_fk', $productosIds)
            ->select(
                'detalle_ingrediente.producto_fk',
                DB::raw('SUM(detalle_ingrediente.cantidad_necesaria * (inventario.precio_proveedor / NULLIF(inventario.cantidad_paquete, 0))) as costo_total_ingredientes')
            )
            ->groupBy('detalle_ingrediente.producto_fk')
            ->get()
            ->keyBy('producto_fk');

        // B. Costo de productos COMPRADOS (directo del inventario)
        // Se usa una subconsulta para obtener solo el costo del último registro de inventario para cada producto
        $subQuery = DB::table('inventario')
            ->select('producto_fk', DB::raw('MAX(inventario_pk) as max_pk'))
            ->whereNotNull('producto_fk')
            ->groupBy('producto_fk');

        $costosComprados = DB::table('inventario as i')
            ->joinSub($subQuery, 'latest_inv', function ($join) {
                $join->on('i.inventario_pk', '=', 'latest_inv.max_pk');
            })
            ->whereIn('i.producto_fk', $productosIds)
            ->select(
                'i.producto_fk',
                DB::raw('i.precio_proveedor / NULLIF(i.cantidad_paquete, 0) as costo_unitario')
            )
            ->get()
            ->keyBy('producto_fk');

        // 4. UNIR DATOS Y CALCULAR RENTABILIDAD (Sin consultas a la BD aquí)
        foreach ($productosVendidos as $producto) {
            $costoUnidad = 0;
            // Verificamos si el producto tiene un costo calculado a partir de ingredientes
            if (isset($costosFabricados[$producto->producto_pk])) {
                $costoUnidad = $costosFabricados[$producto->producto_pk]->costo_total_ingredientes;
            } 
            // Si no, verificamos si tiene un costo como producto comprado
            elseif (isset($costosComprados[$producto->producto_pk])) {
                $costoUnidad = $costosComprados[$producto->producto_pk]->costo_unitario;
            }

            $producto->porcentaje_vendido = $totalVendidas > 0 ? ($producto->cantidad_vendida / $totalVendidas) * 100 : 0;
            $producto->costo_por_unidad = $costoUnidad;
            $producto->costo_total = $costoUnidad * $producto->cantidad_vendida;
            $producto->ganancia_aproximada = $producto->total_recaudado - $producto->costo_total;
        }

        $datos = [
            'productosVendidos' => $productosVendidos,
            'fecha_inicio' => $inicio->format('d/m/Y'),
            'fecha_fin' => $fin->format('d/m/Y'),
        ];

        $pdf = PDF::loadView('formatoReporteProducto', $datos);
        return $pdf->download('reporte_productos_' . now()->format('Ymd_His') . '.pdf');
    }
}
