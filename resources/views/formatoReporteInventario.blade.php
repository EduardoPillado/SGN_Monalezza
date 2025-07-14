<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin-bottom: 70px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Reporte de Consumo de Inventario</h2>
    <p><strong>Desde:</strong> {{ $fecha_inicio }} &nbsp; <strong>Hasta:</strong> {{ $fecha_fin }}</p>

    <table>
        <thead>
            <tr>
                <th>Ingrediente</th>
                <th>Cantidad Consumida</th>
                <th>% Consumido</th>
                <th>Costo Aproximado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($consumoIngredientes as $dato)
                <tr>
                    <td>{{ $dato->nombre_ingrediente }}</td>
                    <td>{{ number_format($dato->cantidad_consumida, 2) }} gr/ml/u</td>
                    <td>{{ number_format($dato->porcentaje_consumido, 2) }}%</td>
                    <td>${{ number_format($dato->costo_aproximado, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay consumo de inventario en este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer" style="position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 10px;">
        <img src="{{ public_path("img/monalezza.jpg") }}" alt="La Monalezza Pizzería" style="height: 80px; vertical-align: middle; margin-right: 5px;">
    </div>
</body>
</html>
