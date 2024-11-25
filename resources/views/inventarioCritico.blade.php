<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Inventario Crítico - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false,
}">

    @php
        use Carbon\Carbon;

        use App\Models\Producto;
        $datosProducto = Producto::where('estatus_producto', 1)
            ->whereNotIn('tipo_producto_fk', [1, 2, 3, 4])
            ->get();

        use App\Models\Ingrediente;
        $datosIngrediente=Ingrediente::where('estatus_ingrediente', '=', 1)->get();

        use App\Models\Tipo_gasto;
        $datosTipoGasto=Tipo_gasto::where('estatus_tipo_gasto', '=', 1)->get();

        use App\Models\Proveedor;
        $datosProveedor=Proveedor::where('estatus_proveedor', '=', 1)->get();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Inventario crítico</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-inventarioCritico" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Ingrediente y/o Producto</th>
                            <th class="text-left py-2">Fecha de ultima actualización</th>
                            <th class="text-left py-2">Cantidad en existencia</th>
                            <th class="text-left py-2">Cantidad de cada paquete</th>
                            <th class="text-left py-2">Restante del último paquete</th>
                            <th class="text-left py-2">Proveedor</th>
                            <th class="text-left py-2">Precio de proveedor</th>
                            <th class="text-left py-2">Tipo de gasto</th>
                            <th class="text-left py-2">Estado</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosInventarioCritico as $dato )
                            <tr class="border-b">
                                @if ( $dato->ingrediente )
                                    <td class="py-2">{{ $dato->ingrediente->nombre_ingrediente }}</td>
                                @elseif ( $dato->producto )
                                    <td class="py-2">{{ $dato->producto->nombre_producto }}</td>
                                @endif
                                <td class="py-2">{{ $dato->fecha_inventario }}</td>
                                <td class="py-2">{{ $dato->cantidad_inventario }} u</td>
                                <td class="py-2">{{ $dato->cantidad_paquete }} gr/ml/u</td>
                                <td class="py-2">{{ $dato->cantidad_parcial }} gr/ml/u</td>
                                <td class="py-2">{{ $dato->proveedor->nombre_proveedor }}</td>
                                <td class="py-2">${{ $dato->precio_proveedor }}</td>
                                @if ( $dato->tipo_gasto )
                                    <td class="py-2">{{ $dato->tipo_gasto->nombre_tipo_gasto }}</td>
                                @else
                                    <td class="py-2"><em>Sin tipo de gasto asociado</em></td>
                                @endif
                                @if ( $dato->cantidad_inventario <= $dato->cantidad_inventario_minima )
                                    <td class="py-2" style="color: red; font-weight: bold;">En riesgo</td>
                                @else
                                    <td class="py-2" style="color: green; font-weight: bold;">Disponible</td>
                                @endif
                                <td class="text-right py-2">
                                    <a href="{{ route('inventario.datosParaEdicion', $dato->inventario_pk) }}" title="Actualizar Stock" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Stock +</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-inventarioCritico').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin stock crítico en el inventario",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    }
                });
            });
        </script>
    </div>
</body>
</html>