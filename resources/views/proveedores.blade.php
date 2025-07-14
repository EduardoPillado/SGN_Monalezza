<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Proveedores - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Proveedores</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-proveedores" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosProveedor as $dato )
                            <tr class="border-b">
                                <td class="py-2">{{ $dato->nombre_proveedor }}</td>
                                @if ( $dato->estatus_proveedor == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                <td class="text-right py-2">
                                    <a href="{{ route('proveedor.datosParaEdicion', $dato->proveedor_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>

                                    @if ($dato->estatus_proveedor == 1)
                                        <a href="{{ route('proveedor.baja', $dato->proveedor_pk) }}" onclick="confirmarBaja(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                    @else
                                        <a href="{{ route('proveedor.alta', $dato->proveedor_pk) }}" onclick="confirmarAlta(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo proveedor</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-proveedores').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin proveedores registrados",
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

            // Alerta de confirmación de baja
            function confirmarBaja(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de baja a este proveedor?',
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
                        text: '¿Deseas dar de alta a este proveedor?',
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

        <!-- Modal de registro de proveedor -->
        <div data-modal style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Proveedor</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-proveedor" action="{{ route('proveedor.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre_proveedor" class="block text-sm font-medium text-gray-700">Nombre
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre_proveedor" name="nombre_proveedor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
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