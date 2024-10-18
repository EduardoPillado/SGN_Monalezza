<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Edición de Empleado - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    @php
        use App\Models\Rol;
        $datosRol=Rol::all();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Edición de Empleado</h1>
            
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mt-2 px-7 py-3">
                            <form action="{{ route('empleado.actualizar', $datosEmpleado->empleado_pk) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="mb-4">
                                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <input type="text" id="nombre" name="nombre" value="{{ $datosEmpleado->usuario->nombre }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="usuario" class="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
                                    <input type="text" id="usuario" name="usuario" value="{{ $datosEmpleado->usuario->usuario }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="contraseña" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                                    <input type="password" id="contraseña" name="contraseña" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="contraseña_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                    <input type="password" id="contraseña_confirmation" name="contraseña_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="rol_fk" class="block text-sm font-medium text-gray-700">Rol del empleado</label>
                                    <select name="rol_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Selecciona el rol del empleado</option>
                                        @foreach ($datosRol as $dato)
                                            <option @if ($dato->rol_fk == $datosEmpleado->rol_pk) selected @endif value="{{ $dato->rol_pk }}">{{ $dato->nombre_rol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="fecha_contratacion" class="block text-sm font-medium text-gray-700">Fecha de Contratación</label>
                                    <input type="date" id="fecha_contratacion" name="fecha_contratacion" value="{{ $datosEmpleado->fecha_contratacion }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="items-center px-4 py-3">
                                    <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        Actualizar
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
    </div>
</body>
</html>