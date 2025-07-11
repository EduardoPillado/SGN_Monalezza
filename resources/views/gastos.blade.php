<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Gastos - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
}">

    @php
        use Carbon\Carbon;

        use App\Models\Tipo_gasto;
        $datosTipoGasto=Tipo_gasto::where('estatus_tipo_gasto', '=', 1)->get();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Gastos</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-gastos" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Tipo de gasto</th>
                            <th class="text-left py-2">Cantidad pagada</th>
                            <th class="text-left py-2">Fecha de pago</th>
                            <th class="text-left py-2">Origen</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosServicio as $dato)
                            <tr class="border-b">
                                <td class="py-2">{{ $dato['tipo_gasto'] }}</td>
                                <td class="py-2">${{ $dato['cantidad_pagada'] }}</td>
                                <td class="py-2">{{ $dato['fecha_pago'] }}</td>
                                <td class="py-2">{{ $dato['origen'] }}</td>
                                <td class="text-right py-2">
                                    <a href="{{ route('gasto.datosParaEdicion', $dato['pk']) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo gasto</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-gastos').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin gastos registrados",
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
                        text: '¿Deseas dar de baja el gasto registrado?',
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
                        text: '¿Deseas dar de alta el gasto registrado?',
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

        <!-- Modal de registro de gasto -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Gasto</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-gasto" action="{{ route('gasto.insertar') }}" method="post">
                            @csrf
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
                                <label for="cantidad_pagada_servicio" class="block text-sm font-medium text-gray-700">Cantidad pagada
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="cantidad_pagada_servicio" name="cantidad_pagada_servicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_pago_servicio" class="block text-sm font-medium text-gray-700">Fecha de pago
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="fecha_pago_servicio" name="fecha_pago_servicio" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
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