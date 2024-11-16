<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Asistencia - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    @php
        use Carbon\Carbon;

        $USUARIO_PK = session('usuario_pk');
        $USUARIO = session('usuario');
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Registro de entrada - Asistencia</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <form action="{{ route('asistencia.registrarEntrada') }}" method="post">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="empleado_fk" class="block text-sm font-medium text-gray-700">Empleado</label>
                            <input type="text" value="{{ $USUARIO }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                            <input type="hidden" name="empleado_fk" value="{{ $USUARIO_PK }}">
                        </div>
                        <div class="mb-4">
                            <label for="fecha_asistencia" class="block text-sm font-medium text-gray-700">Fecha de asistencia</label>
                            <input type="date" id="fecha_asistencia" name="fecha_asistencia" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="hora_entrada" class="block text-sm font-medium text-gray-700">Hora de entrada</label>
                            <input type="time" id="hora_entrada" name="hora_entrada" value="{{ \Carbon\Carbon::now()->format('H:i') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Registrar Entrada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
    <script>
        Swal.fire({
            title: 'Errores de validación',
            html: '{!! implode(' < br > ', $errors->all()) !!}',
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
</body>

</html>