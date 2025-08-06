<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Movimiento de Inventario</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f2f2f2; text-align: center; }
        h2 { text-align: center; }
        .header-info { margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; height: 60px; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de Movimiento de Inventario</h2>
        <div class="header-info">
            <p><strong>Período del reporte:</strong> Del {{ $fecha_inicio }} al {{ $fecha_fin }}</p>
            <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="text-right"><strong>Generado por:</strong> {{ session('usuario') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Elemento</th>
                <th>Tipo</th>
                <th>Stock Inicial</th>
                <th>Entradas (+)</th>
                <th>Salidas Venta (-)</th>
                <th>Stock Final Teórico (=)</th>
                <th>Stock Físico Actual</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movimientosInventario as $dato)
                <tr>
                    <td class="text-left">{{ $dato->nombre }}</td>
                    <td class="text-center">{{ $dato->tipo }}</td>
                    <td class="text-right">{{ number_format($dato->stock_inicial, 2) }} gr/ml/u</td>
                    <td class="text-right">{{ number_format($dato->entradas, 2) }} gr/ml/u</td>
                    <td class="text-right">{{ number_format($dato->salidas_venta, 2) }} gr/ml/u</td>
                    <td class="text-right">{{ number_format($dato->stock_final_teorico, 2) }} gr/ml/u</td>
                    <td class="text-right">{{ number_format($dato->stock_fisico_actual, 2) }} gr/ml/u</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No se encontraron movimientos de inventario en este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <img src="{{ public_path("img/monalezza.jpg") }}" alt="La Monalezza Pizzería" style="height: 80px;">
    </div>
</body>
</html>