<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Nóminas de Empleados - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">
    @php
        use App\Models\Empleado;
        $datosEmpleado=Empleado::where('estatus_empleado', '=', 1)->get();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Nóminas</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <div class="mb-4">
                    <button data-modal-open="modal-filtros" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
                        Filtros de nóminas
                    </button>
                </div>

                <div data-modal="modal-filtros" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 flex">
                    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
                        <h2 class="text-xl font-bold mb-4">Filtrar Nóminas</h2>
                        <form action="{{ route('nomina.filtrar') }}" method="GET" class="space-y-4">
                            <div>
                                <label for="fecha" class="block font-semibold mb-1">Fecha de pago:</label>
                                <input type="date" id="fecha" name="fecha"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Selecciona una fecha"
                                    value="{{ request('fecha') ?? '' }}">
                            </div>

                            <div>
                                <label for="filtro_empleado" class="block font-semibold mb-1">Por empleado:</label>
                                <select name="empleado_fk" id="filtro_empleado" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos los empleados</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->empleado_pk }}" {{ request('empleado_fk') == $empleado->empleado_pk ? 'selected' : '' }}>
                                            {{ $empleado->usuario->usuario }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                    Aplicar
                                </button>
                                <a href="{{ route('nomina.mostrar') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    Quitar filtros
                                </a>
                                <button type="button" data-modal-cancel="modal-filtros" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="tabla-nominas" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Empleado</th>
                            <th class="text-left py-2">Fecha de pago</th>
                            <th class="text-left py-2">Salario base</th>
                            <th class="text-left py-2">Horas extras</th>
                            <th class="text-left py-2">Deducciones</th>
                            <th class="text-left py-2">Compensaciones extra</th>
                            <th class="text-left py-2">Salario neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosNomina as $dato )
                            <tr class="border-b">
                                <td class="py-2">{{ $dato->empleado->usuario->nombre }}</td>
                                <td class="py-2">{{ $dato->fecha_pago }}</td>
                                <td class="py-2">${{ $dato->salario_base }}</td>
                                <td class="py-2">{{ $dato->horas_extra }}</td>
                                <td class="py-2">${{ $dato->deducciones }}</td>
                                <td class="py-2">${{ $dato->compensacion_extra }}</td>
                                <td class="py-2">${{ $dato->salario_neto }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open="modal-form" class="bg-green-500 text-white px-4 py-2 rounded">Generar nómina</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-nominas').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin nóminas registradas",
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

        <!-- Modal de registro de nomina -->
        <div data-modal="modal-form" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full modal-hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Generar Nómina</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-nomina" action="{{ route('nomina.generar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="empleado_fk" class="block text-sm font-medium text-gray-700">Empleado
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="empleado_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona al empleado</option>
                                    @foreach ($datosEmpleado as $dato)
                                        <option value="{{ $dato->empleado_pk }}">{{ $dato->usuario->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="salario_base" class="block text-sm font-medium text-gray-700">Salario
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="salario_base" name="salario_base" value="0.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="compensacion_extra" class="block text-sm font-medium text-gray-700">Compensación extra ($)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="compensacion_extra" name="compensacion_extra" value="0.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha inicial
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha final
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
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