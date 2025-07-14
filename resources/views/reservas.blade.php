<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Reservaciones - La Monalezza</title>
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
            <h1 class="text-2xl font-bold mb-4">Reservaciones</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <div class="mb-4">
                    <button data-modal-open="modal-filtros" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
                        Filtros de reservaciones
                    </button>
                </div>

                <div data-modal="modal-filtros" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 flex">
                    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
                        <h2 class="text-xl font-bold mb-4">Filtrar Reservaciones</h2>
                        <form action="{{ route('reserva.filtrar') }}" method="GET" class="space-y-4">
                            <div>
                                <label for="fecha" class="block font-semibold mb-1">Fecha de reservación:</label>
                                <input type="date" id="fecha" name="fecha"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Selecciona una fecha"
                                    value="{{ request('fecha') ?? '' }}">
                            </div>

                            <div>
                                <label for="cliente_fk" class="block font-semibold mb-1">Por cliente:</label>
                                <select name="cliente_fk" id="cliente_fk" class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Todos los clientes</option>
                                    @foreach($datosCliente as $cliente)
                                        <option value="{{ $cliente->cliente_pk }}" {{ request('cliente_fk') == $cliente->cliente_pk ? 'selected' : '' }}>
                                            {{ $cliente->nombre_cliente }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="estatus" class="block font-semibold mb-1">Por estatus:</label>
                                <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('estatus') == '1' ? 'selected' : '' }}>Pendientes</option>
                                    <option value="0" {{ request('estatus') == '0' ? 'selected' : '' }}>Atendidas</option>
                                    <option value="2" {{ request('estatus') == '2' ? 'selected' : '' }}>Canceladas</option>
                                </select>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                    Aplicar
                                </button>
                                <a href="{{ route('reserva.mostrar') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    Quitar filtros
                                </a>
                                <button type="button" data-modal-cancel="modal-filtros" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

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
                                @if ( $dato->notas )
                                    <td class="py-2">{{ $dato->notas }}</td>
                                @else
                                    <td class="py-2"><em>Sin notas</em></td>
                                @endif
                                @if ( $dato->estatus_reserva == 1 )
                                    <td class="py-2">Pendiente</td>
                                @elseif ( $dato->estatus_reserva == 0 )
                                    <td class="py-2">Atendida</td>
                                @elseif ( $dato->estatus_reserva == 2 )
                                    <td class="py-2">Cancelada</td>
                                @else
                                    <td class="py-2"><em>Sin estatus aplicado</em></td>
                                @endif
                                <td class="text-right py-2">
                                    <a href="{{ route('reserva.datosParaEdicion', $dato->reserva_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded fix">Editar</a>

                                    @if ( $dato->estatus_reserva == 1 )
                                        <a href="{{ route('reserva.atendida', $dato->reserva_pk) }}" onclick="confirmarAtencion(event)" class="bg-green-500 text-white px-2 py-1 rounded fix">Atendida</a>
                                    @endif

                                    @if ( $dato->estatus_reserva != 2 )
                                        <a href="{{ route('reserva.cancelada', $dato->reserva_pk) }}" onclick="confirmarCancelacion(event)" class="bg-red-500 text-white px-2 py-1 rounded fix">Cancelar</a>
                                    @elseif ( $dato->estatus_reserva == 2 )
                                        <a href="{{ route('reserva.pendiente', $dato->reserva_pk) }}" onclick="confirmarDesCancelacion(event)" class="bg-black text-white px-2 py-1 rounded fix">Deshacer cancelación</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open="modal-reservacion" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo reserva</button>
            </div>
            
            <h1 class="text-2xl font-bold mb-4">Mesas</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-reservas" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Número de mesa</th>
                            <th class="text-left py-2">Ubicación</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosMesa as $dato )
                            <tr class="border-b cursor-pointer">
                                <td class="py-2">{{ $dato->numero_mesa }}</td>
                                <td class="py-2">{{ $dato->ubicacion }}</td>
                                @if ( $dato->estatus_mesa == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                <td class="text-right py-2">
                                    <a href="{{ route('mesa.datosParaEdicion', $dato->mesa_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>

                                    @if ($dato->estatus_mesa == 1)
                                        <a href="{{ route('mesa.baja', $dato->mesa_pk) }}" onclick="confirmarBaja(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                    @else
                                        <a href="{{ route('mesa.alta', $dato->mesa_pk) }}" onclick="confirmarAlta(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open="modal-mesa" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nueva mesa</button>
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
                        zeroRecords: "Sin reservaciones registradas",
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

            // Alerta de confirmación de atención
            function confirmarAtencion(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas marcar la reservación como atendida?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, atendida',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }

            // Alerta de confirmación de cancelación
            function confirmarCancelacion(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas marcar la reservación como cancelada?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, cancelar',
                        cancelButtonText: 'No cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }

            // Alerta de confirmación de des cancelación
            function confirmarDesCancelacion(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas deshacer la cancelación?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, deshacer',
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
        <div data-modal="modal-reservacion" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
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
                                <button type="button" data-modal-cancel="modal-reservacion" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
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

        <!-- Modal de registro de nueva mesa -->
        <div data-modal="modal-mesa" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nueva Mesa</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-mesa" action="{{ route('mesa.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="numero_mesa" class="block text-sm font-medium text-gray-700">Número
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="numero_mesa" name="numero_mesa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" id="ubicacion" name="ubicacion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel="modal-mesa" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
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