<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-color: #f0f0f0;
        }

        .receipt {
            background-color: white;
            padding: 10px;
            width: 80mm;
            max-height: 297mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            width: 60px;
            height: auto;
            border-radius: 50%;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin: 10px 0;
        }

        .date {
            text-align: center;
            margin-bottom: 10px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .center-table {
            padding: 20px;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        td {
            white-space: nowrap;
        }

        .descripcion-producto {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }

        .total {
            font-weight: bold;
        }

        .thanks {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
        }

        .pizza-border {
            border-bottom: 5px solid transparent;
            border-image: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M50 0 L100 100 L0 100 Z" fill="%23000"/></svg>') 0 0 5 0 repeat;
            padding-bottom: 5px;
        }

        @media print {

            body,
            .receipt {
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm;
            }
        }
    </style>
</head>

<body>
    @php
        $origen = request('from'); // 'inicio', 'ventas', o null
    @endphp

    <div class="receipt">
        <div class="logo">
            <img src="{{ asset('img/logo_lamonalezza.webp') }}" alt="La Monalezza Logo">
        </div>
        <h1>LA MONALEZZA</h1>
        <p class="date">DOMICILIO ÁNGEL FLORES NO. 55</p>
        <p class="date">{{ $pedido->fecha_hora_pedido->format('d/m/Y - h:i A') }}</p>
        @if($pedido->empleado && $pedido->empleado->usuario)
            <p class="date"><strong>EMPLEADO QUE LO ATENDIÓ:</strong> {{ $pedido->empleado->usuario->nombre }} </p>
        @endif
        <p class="date"><strong>CLIENTE:</strong> {{ $pedido->cliente->nombre_cliente ?? 'Cliente genérico' }} </p>
        <p class="date">FOLIO NO. {{ $pedido->pedido_pk }} </p>
        <div class="center-table">
            <table>
                <tr>
                    <th>ARTÍCULO</th>
                    <th>PRECIO</th>
                </tr>
                @foreach ($pedido->productos as $producto)
                    <tr>
                        <td class="descripcion-producto">
                            x{{ $producto->pivot->cantidad_producto }} {{ $producto->nombre_producto }} ({{ $producto->tipo_producto->nombre_tipo_producto }})
                        </td>
                        <td>$ {{ number_format($producto->precio_producto * $producto->pivot->cantidad_producto, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr class="total">
                    <td>TOTAL</td>
                    <td>$ {{ number_format($pedido->monto_total, 2) }}</td>
                </tr>
                <tr>
                    <td>PAGÓ</td>
                    <td>$ {{ number_format($pedido->pago, 2) }}</td>
                </tr>
                <tr>
                    <td>SU CAMBIO</td>
                    <td>$ {{ number_format($pedido->cambio, 2) }}</td>
                </tr>
            </table>
        </div>
        <p class="thanks">¡GRACIAS POR TU COMPRA!</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>

</html>