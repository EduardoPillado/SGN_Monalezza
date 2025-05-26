<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Edición de Reserva - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    @php
        use App\Models\Cliente;
        $datosCliente=Cliente::all();

        use App\Models\Mesa;
        $datosMesa=Mesa::all();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Edición de Reserva</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <form action="{{ route('reserva.actualizar', $datosReserva->reserva_pk) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="cliente_fk" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="cliente_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Selecciona el cliente</option>
                                @foreach ($datosCliente as $dato)
                                    <option @if ($dato->cliente_pk == $datosReserva->cliente_fk) selected @endif value="{{ $dato->cliente_pk }}">{{ $dato->nombre_cliente }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="fecha_hora_reserva" class="block text-sm font-medium text-gray-700">Fecha y hora de reservación</label>
                            <input type="datetime-local" id="fecha_hora_reserva" name="fecha_hora_reserva" value="{{ $datosReserva->fecha_hora_reserva }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="notas" class="block text-sm font-medium text-gray-700">Notas</label>
                            <textarea id="notas" name="notas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $datosReserva->notas }}</textarea>
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Mesas</label>
                        <div id="mesas-container">
                            @foreach ($datosReserva->mesas as $mesa)
                                <div class="flex items-center mb-2 mesa-row">
                                    <select name="mesas[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Selecciona número de mesa</option>
                                        @foreach ($datosMesa as $dato)
                                            <option @if ($dato->mesa_pk == $mesa->mesa_pk) selected @endif value="{{ $dato->mesa_pk }}">{{ $dato->numero_mesa }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-md remove-mesa">Eliminar</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-mesa" class="mt-2 px-3 py-1 bg-green-500 text-white rounded-md">Agregar Mesa</button>
                    </div>
                
                    <div class="mt-6 text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-mesa').addEventListener('click', function() {
            let container = document.getElementById('mesas-container');
            let newRow = document.createElement('div');
            newRow.classList.add('flex', 'items-center', 'mb-2', 'mesa-row');
            
            newRow.innerHTML = `
                <select name="mesas[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Selecciona número de mesa</option>
                    @foreach ($datosMesa as $dato)
                        <option value="{{ $dato->mesa_pk }}">{{ $dato->numero_mesa }}</option>
                    @endforeach
                </select>

                <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-md remove-mesa">Eliminar</button>
            `;
            
            container.appendChild(newRow);
        });
    
        // Eliminar una mesa específico del formulario
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-mesa')) {
                e.target.parentElement.remove();
            }
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
</body>

</html>