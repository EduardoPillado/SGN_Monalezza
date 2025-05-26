<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Productos - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
}">

    @php
        use App\Models\Tipo_producto;
        $datosTipoProducto=Tipo_producto::all();

        use App\Models\Ingrediente;
        $datosIngrediente=Ingrediente::where('estatus_ingrediente', '=', 1)->get();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Productos</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-productos" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Tipo de producto</th>
                            <th class="text-left py-2">Precio</th>
                            <th class="text-left py-2">Estatus</th>
                            @if ( session('usuario_pk') == 1 )
                                <th class="text-right py-2">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosProducto as $dato)
                            <tr class="border-b cursor-pointer" title="CLIC PARA VER DETALLES" 
                                data-ingredientes='@json($dato->ingredientes)'>
                                <td class="py-2">{{ $dato->nombre_producto }}</td>
                                <td class="py-2">{{ $dato->tipo_producto->nombre_tipo_producto }}</td>
                                <td class="py-2">${{ $dato->precio_producto }}</td>
                                <td class="py-2">{{ $dato->estatus_producto ? 'Activo' : 'Inactivo' }}</td>
                                @if ( session('usuario_pk') == 1 )
                                    <td class="text-right py-2">
                                        <a href="{{ route('producto.datosParaEdicion', $dato->producto_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>
                                        @if ($dato->estatus_producto)
                                            <a href="{{ route('producto.baja', $dato->producto_pk) }}" onclick="confirmarBaja(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                        @else
                                            <a href="{{ route('producto.alta', $dato->producto_pk) }}" onclick="confirmarAlta(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo producto</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                const table = $('#tabla-productos').DataTable({
                    language: {
                        search: "Buscar:",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        zeroRecords: "Sin productos registrados",
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });

                // Añadir el evento de clic para expandir detalles
                $('#tabla-productos tbody').on('click', 'tr', function () {
                    const row = table.row(this);
                    const ingredientes = $(this).data('ingredientes'); // Acceder a los ingredientes desde el atributo data-ingredientes

                    if (row.child.isShown()) {
                        // Si está expandido, lo oculta
                        row.child.hide();
                        $(this).removeClass('shown');
                    } else {
                        // Si no está expandido, lo muestra
                        row.child(formatDetails(ingredientes)).show();
                        $(this).addClass('shown');
                    }
                });

                // Función para formatear el contenido de los detalles
                function formatDetails(ingredientes) {
                    let contenido = 'No hay ingredientes asociados.';
                    
                    if (ingredientes && ingredientes.length > 0) {
                        contenido = ingredientes
                            .map(ingrediente => `${ingrediente.nombre_ingrediente} (${ingrediente.pivot.cantidad_necesaria} gr/ml)`)
                            .join(', ');
                    }

                    return `<div class="p-4 bg-gray-50">
                                <strong>Ingredientes:</strong> ${contenido}
                            </div>`;
                }
            });

            // Alerta de confirmación de baja
            function confirmarBaja(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de baja a este producto?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, dar de baja',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }

            // Alerta de confirmación de alta
            function confirmarAlta(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de alta a este producto?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, dar de alta',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }
        </script>

        <!-- Modal de registro de producto -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Producto</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">

                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-producto" action="{{ route('producto.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre_producto" class="block text-sm font-medium text-gray-700">Nombre
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre_producto" name="nombre_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="tipo_producto_fk" class="block text-sm font-medium text-gray-700">Tipo de producto
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo_producto_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona el tipo de producto</option>
                                    @foreach ($datosTipoProducto as $dato)
                                        <option value="{{ $dato->tipo_producto_pk }}">{{ $dato->nombre_tipo_producto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="precio_producto" class="block text-sm font-medium text-gray-700">Precio
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="precio_producto" name="precio_producto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <div class="mb-4">
                                <div id="ingredientes-container">
                                    <div class="flex items-center mb-2">
                                        <div class="flex flex-col w-3/4">
                                            <label for="ingredientes[]" class="block text-sm font-medium text-gray-700">Ingrediente
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <select name="ingredientes[]" class="w-full rounded-md border-gray-300 mb-2">
                                                <option value="">Selecciona un ingrediente</option>
                                                @foreach ($datosIngrediente as $dato)
                                                    <option value="{{ $dato->ingrediente_pk }}">{{ $dato->nombre_ingrediente }}</option>
                                                @endforeach
                                            </select>
                            
                                            <label for="cantidades_necesarias[]" class="block text-sm font-medium text-gray-700">Cantidad requerida (gr/ml)
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="cantidades_necesarias[]" class="w-full rounded-md border-gray-300">
                                        </div>
                                        <div class="flex w-1/4 justify-center">
                                            <button type="button" onclick="agregarIngrediente()" class="px-3 py-1 bg-blue-500 text-white rounded">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="items-center px-4 py-3">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function agregarIngrediente() {
                const container = document.getElementById('ingredientes-container');
                const newIngredient = document.createElement('div');
                
                newIngredient.classList.add('flex', 'items-center', 'mb-2');
                
                newIngredient.innerHTML = `
                    <div class="flex flex-col w-3/4">
                        <select name="ingredientes[]" class="w-full rounded-md border-gray-300 mb-2">
                            <option value="">Selecciona un ingrediente</option>
                            @foreach ($datosIngrediente as $dato)
                                <option value="{{ $dato->ingrediente_pk }}">{{ $dato->nombre_ingrediente }}</option>
                            @endforeach
                        </select>
        
                        <label class="block text-sm font-medium text-gray-700">Cantidad requerida (gr/ml)</label>
                        <input type="number" name="cantidades_necesarias[]" class="w-full rounded-md border-gray-300">
                    </div>
                    <div class="flex w-1/4 justify-center">
                        <button type="button" onclick="eliminarIngrediente(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                    </div>
                `;
        
                container.appendChild(newIngredient);
            }
        
            function eliminarIngrediente(button) {
                button.parentNode.parentNode.remove();
            }
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