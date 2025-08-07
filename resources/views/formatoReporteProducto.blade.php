<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos Vendidos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin-bottom: 70px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .header-info { margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; height: 60px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Reporte de Productos Vendidos</h2>
    <div class="header-info">
        <p><strong>Período del reporte:</strong> Del {{ $fecha_inicio }} al {{ $fecha_fin }}</p>
        <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p class="text-right"><strong>Generado por:</strong> {{ session('usuario') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Tipo Producto</th>
                <th>Cantidad Vendida</th>
                <th>% Vendido</th>
                <th>Precio Unitario ($)</th>
                <th>Total Recaudado ($)</th>
                <th>Costo por Unidad ($)</th>
                <th>Costo Total ($)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productosVendidos as $dato)
                <tr>
                    <td>{{ $dato->nombre_producto }}</td>
                    <td>{{ $dato->nombre_tipo_producto }}</td>
                    <td>{{ $dato->cantidad_vendida }}</td>
                    <td>{{ number_format($dato->porcentaje_vendido, 2) }}%</td>
                    <td>${{ number_format($dato->precio_producto, 2) }}</td>
                    <td>${{ number_format($dato->total_recaudado, 2) }}</td>
                    <td>${{ number_format($dato->costo_por_unidad, 2) }}</td>
                    <td>${{ number_format($dato->costo_total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay productos vendidos en este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <img src="{{ public_path("img/monalezza.jpg") }}" alt="La Monalezza Pizzería" style="height: 80px;">
    </div>
</body>
</html>
