<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos Vendidos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin-bottom: 70px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    @php
        $USUARIO = session('usuario');
    @endphp

    <h2>Reporte de Productos Vendidos</h2>
    <p><strong>Desde:</strong> {{ $fecha_inicio }} &nbsp;&nbsp; <strong>Hasta:</strong> {{ $fecha_fin }}</p>
    <p style="text-align: right;"><strong>Generado por:</strong> {{ $USUARIO }}</p>

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

    <div class="footer" style="position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 10px;">
        <img src="{{ public_path("img/monalezza.jpg") }}" alt="La Monalezza Pizzería" style="height: 80px;">
    </div>
</body>
</html>
