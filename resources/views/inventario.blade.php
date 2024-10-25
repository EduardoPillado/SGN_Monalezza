<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Inventario - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
    editModalOpen: false,
}">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Inventario</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-inventario" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Ingrediente y/o Producto</th>
                            <th class="text-left py-2">Fecha de ultima actualización</th>
                            <th class="text-left py-2">Cantidad en existencia</th>
                            <th class="text-left py-2">Tipo de gasto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosInventario as $dato )
                            <tr class="border-b">
                                @if ( $dato->ingrediente )
                                    <td class="py-2">{{ $dato->ingrediente->nombre_ingrediente }}</td>
                                @elseif ( $dato->producto )
                                    <td class="py-2">{{ $dato->producto->nombre_producto }}</td>
                                @endif
                                <td class="py-2">{{ $dato->fecha_inventario }}</td>
                                <td class="py-2">${{ $dato->cantidad_inventario }}</td>
                                @if ( $dato->gasto )
                                    <td class="py-2">{{ $dato->gasto->tipo_gasto->nombre_tipo_gasto }}</td>
                                @else
                                    <td class="py-2"><em>Sin gasto asociado</em></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar en inventario</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-inventario').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin registros en inventario",
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

        <!-- Modal de registro en inventario -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar en Inventario</h3>
                    <div class="mt-2 px-7 py-3">
                        {{-- <form id="form-inventario" action="{{ route('inventario.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre_producto" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre_producto" name="nombre_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="tipo_producto_fk" class="block text-sm font-medium text-gray-700">Tipo de producto</label>
                                <select name="tipo_producto_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona el tipo de producto</option>
                                    @foreach ($datosTipoProducto as $dato)
                                        <option value="{{ $dato->tipo_producto_pk }}">{{ $dato->nombre_tipo_producto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="precio_producto" class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" id="precio_producto" name="precio_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="proveedor_fk" class="block text-sm font-medium text-gray-700">Proveedor del producto</label>
                                <select name="proveedor_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Selecciona el proveedor</option>
                                    @foreach ($datosProveedor as $dato)
                                        <option value="{{ $dato->proveedor_pk }}">{{ $dato->nombre_proveedor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Guardar
                                </button>
                            </div>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <script>
                Swal.fire({
                    title: 'Errores de validación',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            </script>
        @endif
    </div>
</body>
</html>