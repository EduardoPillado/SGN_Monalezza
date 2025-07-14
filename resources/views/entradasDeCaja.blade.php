<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Entradas de Caja - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Entradas de Caja</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-entradas-caja" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Tipo</th>
                            <th class="text-left py-2">Monto</th>
                            <th class="text-left py-2">Concepto</th>
                            <th class="text-left py-2">Fecha</th>
                            <th class="text-left py-2">Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosEntradaCaja as $dato )
                            <tr class="border-b">
                                <td class="py-2">{{ $dato->tipo_entrada_caja }}</td>
                                <td class="py-2">{{ $dato->monto_entrada_caja }}</td>
                                @if ( $dato->concepto_entrada_caja )
                                    <td class="py-2">{{ $dato->concepto_entrada_caja }}</td>
                                @else
                                    <td class="py-2"><em>Sin concepto añadido</em></td>
                                @endif
                                <td class="py-2">{{ $dato->fecha_entrada_caja }}</td>
                                <td class="py-2">{{ $dato->usuario->usuario }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open class="bg-green-500 text-white px-4 py-2 rounded">Registrar nueva entrada de caja</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-entradas-caja').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin entradas de caja registradas",
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

        <!-- Modal de registro de entrada de caja -->
        <div data-modal style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nueva Entrada de Caja</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-entrada-caja" action="{{ route('entradas_caja.insertar') }}" method="post">
                            @csrf

                            <div class="mb-4">
                                <label for="tipo_entrada_caja" class="block text-sm font-medium text-gray-700">Tipo de entrada
                                    <span class="text-red-500">*</span>
                                </label>
                                <select id="tipo_entrada_caja" name="tipo_entrada_caja"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                    <option value="Entrada">Entrada</option>
                                    <option value="Inicial">Inicial</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="monto_entrada_caja" class="block text-sm font-medium text-gray-700">Monto
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="0.01" id="monto_entrada_caja" name="monto_entrada_caja"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="concepto_entrada_caja" class="block text-sm font-medium text-gray-700">Concepto</label>
                                <input type="text" id="concepto_entrada_caja" name="concepto_entrada_caja"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="fecha_entrada_caja" class="block text-sm font-medium text-gray-700">Fecha y hora
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="fecha_entrada_caja" name="fecha_entrada_caja" 
                                    value="{{ now()->format('Y-m-d\TH:i') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                            </div>

                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel
                                    class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
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