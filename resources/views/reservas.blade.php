<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Reservas - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
}">

    @php
        use App\Models\Cliente;
        $datosCliente=Cliente::all();

        use App\Models\Mesa;
        $datosMesa=Mesa::all();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Reservas</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-reservas" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Cliente</th>
                            <th class="text-left py-2">Fecha y hora de reservación</th>
                            <th class="text-left py-2">Notas</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosReserva as $dato )
                            <tr class="border-b cursor-pointer" title="CLIC PARA VER DETALLES" 
                                data-mesas='@json($dato->mesas)'>
                                <td class="py-2">{{ $dato->cliente->nombre_cliente }}</td>
                                <td class="py-2">{{ $dato->fecha_hora_reserva }}</td>
                                <td class="py-2">{{ $dato->notas }}</td>
                                @if ( $dato->estatus_reserva == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                <td class="text-right py-2">
                                    <a href="{{ route('reserva.datosParaEdicion', $dato->reserva_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>

                                    @if ($dato->estatus_reserva == 1)
                                        <a href="{{ route('reserva.baja', $dato->reserva_pk) }}" onclick="confirmarBaja(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                    @else
                                        <a href="{{ route('reserva.alta', $dato->reserva_pk) }}" onclick="confirmarAlta(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo reserva</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                const table = $('#tabla-reservas').DataTable({
                    language: {
                        search: "Buscar:",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        zeroRecords: "Sin reservas registradas",
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
                $('#tabla-reservas tbody').on('click', 'tr', function () {
                    const row = table.row(this);
                    const mesas = $(this).data('mesas');

                    if (row.child.isShown()) {
                        // Si está expandido, lo oculta
                        row.child.hide();
                        $(this).removeClass('shown');
                    } else {
                        // Si no está expandido, lo muestra
                        row.child(formatDetails(mesas)).show();
                        $(this).addClass('shown');
                    }
                });

                // Función para formatear el contenido de los detalles
                function formatDetails(mesas) {
                    let contenido = 'No hay mesas reservadas.';
                    
                    if (mesas && mesas.length > 0) {
                        contenido = mesas
                            .map(mesa => `${mesa.numero_mesa} (${mesa.ubicacion})`)
                            .join(', ');
                    }

                    return `<div class="p-4 bg-gray-50">
                                <strong>Mesas reservadas:</strong> ${contenido}
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
                        text: '¿Deseas dar de baja la reserva?',
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
                        text: '¿Deseas dar de alta la reserva?',
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

        <!-- Modal de registro de reserva -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nueva Reserva</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-reserva" action="{{ route('reserva.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="cliente_fk" class="block text-sm font-medium text-gray-700">Cliente
                                    <span class="text-red-500">*</span>

                                </label>
                                <select name="cliente_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Selecciona el cliente</option>
                                    @foreach ($datosCliente as $dato)
                                        <option value="{{ $dato->cliente_pk }}">{{ $dato->nombre_cliente }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_hora_reserva" class="block text-sm font-medium text-gray-700">Fecha y hora de reservación
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="fecha_hora_reserva" name="fecha_hora_reserva" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="notas" class="block text-sm font-medium text-gray-700">Notas</label>
                                <textarea name="notas" id="notas" cols="30" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div class="mb-4">
                                <div id="mesas-container">
                                    <div class="flex items-center mb-2">
                                        <div class="flex flex-col w-3/4">
                                            <label for="mesas[]" class="block text-sm font-medium text-gray-700">Mesa
                                                <span class="text-red-500">*</span>

                                            </label>
                                            <select name="mesas[]" class="w-full rounded-md border-gray-300 mb-2" required>
                                                <option value="">Selecciona número de mesa</option>
                                                @foreach ($datosMesa as $dato)
                                                    <option value="{{ $dato->mesa_pk }}">{{ $dato->numero_mesa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex w-1/4 justify-center">
                                            <button type="button" onclick="agregarMesa()" class="px-3 py-1 bg-blue-500 text-white rounded">+</button>
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
            function agregarMesa() {
                const container = document.getElementById('mesas-container');
                const newMesa = document.createElement('div');
                
                newMesa.classList.add('flex', 'items-center', 'mb-2');
                
                newMesa.innerHTML = `
                    <div class="flex flex-col w-3/4">
                        <select name="mesas[]" class="w-full rounded-md border-gray-300 mb-2" required>
                            <option value="">Selecciona número de mesa</option>
                            @foreach ($datosMesa as $dato)
                                <option value="{{ $dato->mesa_pk }}">{{ $dato->numero_mesa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex w-1/4 justify-center">
                        <button type="button" onclick="eliminarMesa(this)" class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                    </div>
                `;
        
                container.appendChild(newMesa);
            }
        
            function eliminarMesa(button) {
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