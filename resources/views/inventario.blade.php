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

<body class="h-full bg-gray-100 overflow-hidden">

    @php
        use Carbon\Carbon;

        use App\Models\Tipo_producto;
        $tiposExcluidos = Tipo_producto::where(function($query) {
            $query->where('nombre_tipo_producto', 'like', '%pizza%')
                ->orWhere('nombre_tipo_producto', 'like', '%mediana%')
                ->orWhere('nombre_tipo_producto', 'like', '%familiar%')
                ->orWhere('nombre_tipo_producto', 'like', '%mega%')
                ->orWhere('nombre_tipo_producto', 'like', '%cuadrada%');
        })->pluck('tipo_producto_pk');

        use App\Models\Producto;
        $datosProducto = Producto::where('estatus_producto', '=', 1)
            ->whereNotIn('tipo_producto_fk', $tiposExcluidos)
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
            <h1 class="text-2xl font-bold mb-4">Inventario</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <div class="mb-4">
                    <button data-modal-open="modal-filtros" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
                        Filtros de inventario
                    </button>
                </div>

                <div data-modal="modal-filtros" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 flex">
                    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
                        <h2 class="text-xl font-bold mb-4">Filtrar Inventario</h2>
                        <form action="{{ route('inventario.filtrar') }}" method="GET" class="space-y-4">
                            <div>
                                <label for="fecha" class="block font-semibold mb-1">Fecha de última actualización:</label>
                                <input type="date" id="fecha" name="fecha"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Selecciona una fecha"
                                    value="{{ request('fecha') ?? '' }}">
                            </div>

                            <div>
                                <label for="tipo_elemento_filtrar" class="block font-semibold mb-1">Por producto o ingrediente:</label>
                                <select name="tipo_elemento_filtrar" id="tipo_elemento_filtrar" class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Producto e Ingrediente</option>
                                    <option value="producto" {{ request('tipo_elemento_filtrar') == 'producto' ? 'selected' : '' }}>Producto</option>
                                    <option value="ingrediente" {{ request('tipo_elemento_filtrar') == 'ingrediente' ? 'selected' : '' }}>Ingrediente</option>
                                </select>
                            </div>

                            <div>
                                <label for="proveedor_fk" class="block font-semibold mb-1">Por proveedor:</label>
                                <select name="proveedor_fk" id="proveedor_fk" class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Todos los proveedores</option>
                                    @foreach($datosProveedor as $proveedor)
                                        <option value="{{ $proveedor->proveedor_pk }}" {{ request('proveedor_fk') == $proveedor->proveedor_pk ? 'selected' : '' }}>
                                            {{ $proveedor->nombre_proveedor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="estado" class="block font-semibold mb-1">Estado:</label>
                                <select name="estado" id="estado" class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Todos</option>
                                    <option value="riesgo" {{ request('estado') == 'riesgo' ? 'selected' : '' }}>En riesgo</option>
                                    <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                </select>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                    Aplicar
                                </button>
                                <a href="{{ route('inventario.mostrar') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    Quitar filtros
                                </a>
                                <button type="button" data-modal-cancel="modal-filtros" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="tabla-inventario" class="w-full">
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
                            @if ( session('rol_pk') == 1 )
                                <th class="text-right py-2">Acciones</th>
                            @endif
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
                                <td class="py-2">{{ $dato->cantidad_inventario }} paq</td>
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
                                @if ( session('rol_pk') == 1 )
                                    <td class="text-right py-2">
                                        <a href="{{ route('inventario.datosParaEdicion', $dato->inventario_pk) }}" title="Actualizar Stock" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Stock +</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open="modal-form" class="bg-green-500 text-white px-4 py-2 rounded">Agregar Stock</button>
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
        <div data-modal="modal-form" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar en Inventario</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario
                        </p>
                        <form id="form-inventario" action="{{ route('inventario.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="tipo_elemento" class="block text-sm font-medium text-gray-700">Agregar a inventario
                                    <span class="text-red-500">*</span>
                                </label>
                                <select id="tipo_elemento" name="tipo_elemento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required onchange="toggleFields()">
                                    <option value="">Selecciona que se agregará a stock</option>
                                    <option value="producto">Producto</option>
                                    <option value="ingrediente">Ingrediente</option>
                                </select>
                            </div>
                            
                            <div id="producto_field" class="mb-4 hidden">
                                <label for="producto_fk" class="block text-sm font-medium text-gray-700">Producto</label>
                                <select name="producto_fk" id="producto_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Selecciona un producto</option>
                                    @foreach ($datosProducto as $producto)
                                        <option value="{{ $producto->producto_pk }}">{{ $producto->nombre_producto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="ingrediente_field" class="mb-4 hidden">
                                <label for="ingrediente_fk" class="block text-sm font-medium text-gray-700">Ingrediente</label>
                                <select name="ingrediente_fk" id="ingrediente_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Selecciona un ingrediente</option>
                                    @foreach ($datosIngrediente as $ingrediente)
                                        <option value="{{ $ingrediente->ingrediente_pk }}">{{ $ingrediente->nombre_ingrediente }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="tipo_gasto_fk" class="block text-sm font-medium text-gray-700">Tipo de gasto
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo_gasto_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona el tipo de gasto</option>
                                    @foreach ($datosTipoGasto as $dato)
                                        <option value="{{ $dato->tipo_gasto_pk }}">{{ $dato->nombre_tipo_gasto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="proveedor_fk" class="block text-sm font-medium text-gray-700">Proveedor del producto
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="proveedor_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Selecciona el proveedor</option>
                                    @foreach ($datosProveedor as $dato)
                                        <option value="{{ $dato->proveedor_pk }}">{{ $dato->nombre_proveedor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="precio_proveedor" class="block text-sm font-medium text-gray-700">Precio del proveedor por paquete
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="precio_proveedor" name="precio_proveedor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="cantidad_inventario" class="block text-sm font-medium text-gray-700">
                                    <span class="text-blue-500 text-lg cursor-help" title="Cantidad de paquetes a agregar al inventario">!</span>
                                    Cantidad de stock
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="cantidad_inventario" name="cantidad_inventario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="cantidad_paquete" class="block text-sm font-medium text-gray-700">
                                    <span class="text-blue-500 text-lg cursor-help" title="Cantidad que contiene el paquete, sean gramos, mililitros, o unidades">!</span>
                                    Cantidad del paquete (gr/ml/u)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="cantidad_paquete" name="cantidad_paquete" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="cantidad_inventario_minima" class="block text-sm font-medium text-gray-700">
                                    <span class="text-blue-500 text-lg cursor-help" title="Cantidad de paquetes antes de alerta de poco stock">!</span>
                                    Cantidad minima de stock
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="cantidad_inventario_minima" name="cantidad_inventario_minima" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_inventario" class="block text-sm font-medium text-gray-700">Fecha de inventario
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="fecha_inventario" name="fecha_inventario" value="{{ Carbon::now()->format('Y-m-d\TH:i') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel="modal-form" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Guardar
                                </button>
                            </div>
                        </form>
                        <script>
                            function toggleFields() {
                                const tipoElemento = document.getElementById('tipo_elemento').value;
                                document.getElementById('producto_field').classList.toggle('hidden', tipoElemento !== 'producto');
                                document.getElementById('ingrediente_field').classList.toggle('hidden', tipoElemento !== 'ingrediente');
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Abrir modal según su nombre
                document.querySelectorAll('[data-modal-open]').forEach(button => {
                    button.addEventListener('click', function () {
                        const modalName = this.getAttribute('data-modal-open');
                        const modal = document.querySelector(`[data-modal="${modalName}"]`);
                        if (modal) modal.style.display = 'block';
                    });
                });

                // Cerrar modal desde botón de cancelar
                document.querySelectorAll('[data-modal-cancel]').forEach(button => {
                    button.addEventListener('click', function () {
                        const modalName = this.getAttribute('data-modal-cancel');
                        const modal = document.querySelector(`[data-modal="${modalName}"]`);
                        if (modal) modal.style.display = 'none';
                    });
                });

                // Cerrar modal haciendo click fuera del contenido
                window.addEventListener('click', function (event) {
                    document.querySelectorAll('[data-modal]').forEach(modal => {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                });

                // Activar flatpickr
                flatpickr("#fecha", {
                    dateFormat: "Y-m-d",
                    defaultDate: "{{ request('fecha') ?? '' }}",
                    maxDate: "today"
                });
            });
        </script>

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