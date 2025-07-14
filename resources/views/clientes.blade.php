<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Clientes - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">
    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Clientes</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-clientes" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Domicilio</th>
                            <th class="text-left py-2">Referencias</th>
                            <th class="text-left py-2">Teléfono</th>
                            @if ( session('rol_pk') == 1 )
                                <th class="text-right py-2">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosCliente as $dato )
                        <tr class="border-b">
                            <td class="py-2">{{ $dato->nombre_cliente }}</td>
                            <td class="py-2">
                                Calle {{ $dato->domicilio->calle }}, #{{ $dato->domicilio->numero_externo }} externo
                                @if ($dato->domicilio->numero_interno)
                                    , #{{ $dato->domicilio->numero_interno }} interno
                                @endif
                            </td>
                            <td class="py-2">
                                @if ($dato->domicilio->referencias)
                                    {{ $dato->domicilio->referencias }}
                                @else
                                    <em>Sin referencias</em>
                                @endif
                            </td>
                            <td class="py-2">{{ $dato->telefono->telefono }}</td>
                            @if ( session('rol_pk') == 1 )
                                <td class="text-right py-2">
                                    <a href="{{ route('cliente.datosParaEdicion', $dato->cliente_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo cliente</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-clientes').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin clientes registrados",
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

        <!-- Modal de registro de cliente -->
        <div data-modal style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Cliente</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-cliente" action="{{ route('cliente.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre_cliente" class="block text-sm font-medium text-gray-700">Nombre
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre_cliente" name="nombre_cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="calle" class="block text-sm font-medium text-gray-700">Calle del domicilio
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="calle" name="calle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="numero_externo" class="block text-sm font-medium text-gray-700">Número externo
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="numero_externo" name="numero_externo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="numero_interno" class="block text-sm font-medium text-gray-700">Número interno</label>
                                <input type="number" id="numero_interno" name="numero_interno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="referencias" class="block text-sm font-medium text-gray-700">Referencias</label>
                                <textarea id="referencias" name="referencias" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="telefono" name="telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
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
            document.addEventListener('DOMContentLoaded', function() {
                const openModalBtn = document.querySelector('[data-modal-open]');
                const modal = document.querySelector('[data-modal]');
                const cancelBtn = document.querySelector('[data-modal-cancel]');

                // Función para abrir el modal
                function openModal() {
                    modal.style.display = 'block';
                }

                // Función para cerrar el modal
                function closeModal() {
                    modal.style.display = 'none';
                }

                // Event listeners
                openModalBtn.addEventListener('click', openModal);
                cancelBtn.addEventListener('click', closeModal);

                // Cerrar modal si se hace click fuera de él
                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        closeModal();
                    }
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