<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Empleados - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
    editModalOpen: false,
}">

    @php
        use Carbon\Carbon;

        use App\Models\Rol;
        $datosRol=Rol::all();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Empleados</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-empleados" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Usuario</th>
                            <th class="text-left py-2">Rol</th>
                            <th class="text-left py-2">Fecha Contratación</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosEmpleado as $dato )
                            <tr class="border-b">
                                <td class="py-2">{{ $dato->usuario->nombre }}</td>
                                <td class="py-2">{{ $dato->usuario->usuario }}</td>
                                <td class="py-2">{{ $dato->usuario->rol->nombre_rol }}</td>
                                <td class="py-2">{{ $dato->fecha_contratacion }}</td>
                                @if ( $dato->estatus_empleado == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                <td class="text-right py-2">
                                    <a href="{{ route('empleado.datosParaEdicion', $dato->empleado_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>

                                    @if ($dato->estatus_empleado == 1 & $dato->usuario->estatus_usuario == 1)
                                        <a href="{{ route('empleado.baja', $dato->empleado_pk) }}" onclick="confirmarBaja(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                    @else
                                        <a href="{{ route('empleado.alta', $dato->empleado_pk) }}" onclick="confirmarAlta(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo empleado</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-empleados').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "zeroRecords": "Sin resultados",
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
                        text: '¿Deseas dar de baja a este empleado?',
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
                        text: '¿Deseas dar de alta a este empleado?',
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

        <!-- Modal de registro de empleado -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Empleado</h3>
                    <div class="mt-2 px-7 py-3">
                        <form id="form-empleado" action="{{ route('empleado.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="usuario" class="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
                                <input type="text" id="usuario" name="usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="contraseña" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <input type="password" id="contraseña" name="contraseña" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="rol_fk" class="block text-sm font-medium text-gray-700">Rol del empleado</label>
                                <select name="rol_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona el rol del empleado</option>
                                    @foreach ($datosRol as $dato)
                                        <option value="{{ $dato->rol_pk }}">{{ $dato->nombre_rol }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_contratacion" class="block text-sm font-medium text-gray-700">Fecha de Contratación</label>
                                <input type="date" id="fecha_contratacion" name="fecha_contratacion" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
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

        <!-- Modal de edición de empleado -->
        {{-- <div x-show="editModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Editar Empleado</h3>
                    <div class="mt-2 px-7 py-3">
                        <form action="{{ route('empleado.actualizar', $datosEmpleado->empleado_pk) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-4">
                                <label for="edit_nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="edit_nombre" name="nombre" value="{{ $datosEmpleado->usuario->nombre }}" x-model="editingEmployee.nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_usuario" class="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
                                <input type="text" id="edit_usuario" name="usuario" value="{{ $datosEmpleado->usuario->usuario }}" x-model="editingEmployee.usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_contraseña" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                                <input type="password" id="edit_contraseña" name="contraseña" x-model="editingEmployee.contraseña" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_confirmar_contraseña" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                <input type="password" id="edit_confirmar_contraseña" name="confirmar_contraseña" x-model="editingEmployee.confirmar_contraseña" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_jrol_fk" class="block text-sm font-medium text-gray-700">Rol del empleado</label>
                                <select name="rol_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona el rol del empleado</option>
                                    @foreach ($datosRol as $dato)
                                        <option @if ($dato->rol_fk == $datosEmpleado->rol_pk) selected @endif value="{{ $dato->rol_pk }}">{{ $dato->nombre_rol }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="edit_fecha_contratacion" class="block text-sm font-medium text-gray-700">Fecha de Contratación</label>
                                <input type="date" id="edit_fecha_contratacion" name="fecha_contratacion" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" x-model="editingEmployee.fecha_contratacion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</body>
</html>