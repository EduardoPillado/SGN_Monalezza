<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class Reporte_controller extends Controller
{
    public function generarReporteInventario(Request $req){
        $req->validate([
            'fecha_reporte_inventario_inicio' => 'required|date',
            'fecha_reporte_inventario_fin' => 'required|date|after_or_equal:fecha_reporte_inventario_inicio',
        ]);

        $inicio = Carbon::parse($req->fecha_reporte_inventario_inicio)->startOfDay();
        $fin = Carbon::parse($req->fecha_reporte_inventario_fin)->endOfDay();

        // Consumo de ingredientes en pedidos entregados
        $consumo = DB::table('detalle_pedido')
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

        // Agregar datos de inventario actual para calcular porcentaje y costo
        foreach ($consumo as $item) {
            $inventario = DB::table('inventario')
                ->where('ingrediente_fk', $item->ingrediente_pk)
                ->select(
                    DB::raw('SUM(cantidad_inventario * cantidad_paquete + cantidad_parcial) as total_disponible'),
                    DB::raw('AVG(precio_proveedor / cantidad_paquete) as precio_unitario_prom')
                )
                ->first();

            $item->total_disponible = $inventario->total_disponible ?? 0;
            $item->porcentaje_consumido = $item->total_disponible > 0
                ? round(($item->cantidad_consumida / $item->total_disponible) * 100, 2)
                : 0;

            $item->costo_aproximado = $item->cantidad_consumida * ($inventario->precio_unitario_prom ?? 0);
        }

        $datos = [
            'consumoIngredientes' => $consumo,
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
        $totalVendidas = $productosVendidos->sum('cantidad_vendida');

        // Obtener ingredientes usados por producto
        $datosCalculados = [];
        foreach ($productosVendidos as $producto) {
            $costoUnidad = 0;

            // Obtener ingredientes
            $ingredientes = DB::table('detalle_ingrediente')
                ->join('ingrediente', 'detalle_ingrediente.ingrediente_fk', '=', 'ingrediente.ingrediente_pk')
                ->join('inventario', 'ingrediente.ingrediente_pk', '=', 'inventario.ingrediente_fk')
                ->where('detalle_ingrediente.producto_fk', $producto->producto_pk)
                ->select(
                    'ingrediente.nombre_ingrediente',
                    'detalle_ingrediente.cantidad_necesaria',
                    DB::raw('AVG(inventario.precio_proveedor / inventario.cantidad_paquete) as precio_unitario')
                )
                ->groupBy('ingrediente.nombre_ingrediente', 'detalle_ingrediente.cantidad_necesaria')
                ->get();

            if ($ingredientes->count()) {
                foreach ($ingredientes as $ingrediente) {
                    $costoUnidad += $ingrediente->cantidad_necesaria * $ingrediente->precio_unitario;
                }
            } else {
                // No hay ingredientes: buscar precio en inventario como producto
                $precioInventario = DB::table('inventario')
                    ->where('producto_fk', $producto->producto_pk)
                    ->orderByDesc('fecha_inventario')
                    ->selectRaw('precio_proveedor / NULLIF(cantidad_paquete, 0) as precio_unitario')
                    ->first();

                $costoUnidad = $precioInventario->precio_unitario ?? 0;
            }

            $producto->porcentaje_vendido = $totalVendidas > 0 ? ($producto->cantidad_vendida / $totalVendidas) * 100 : 0;
            $producto->costo_por_unidad = $costoUnidad;
            $producto->costo_total = $costoUnidad * $producto->cantidad_vendida;

            $datosCalculados[] = $producto;
        }

        $datos = [
            'productosVendidos' => $datosCalculados,
            'fecha_inicio' => $inicio->format('d/m/Y'),
            'fecha_fin' => $fin->format('d/m/Y'),
        ];

        $pdf = PDF::loadView('formatoReporteProducto', $datos);
        return $pdf->download('reporte_productos_' . now()->format('Ymd_His') . '.pdf');
    }
}
