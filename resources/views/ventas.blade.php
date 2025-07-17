<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Ventas - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Ventas</h1>
            <div class="bg-white shadow-md rounded-lg p-4 mb-10">
                <div class="mb-4">
                    <button data-modal-open="modal-filtros" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
                        Filtros de ventas
                    </button>
                </div>

                <div data-modal="modal-filtros" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 flex">
                    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
                        <h2 class="text-xl font-bold mb-4">Filtrar Ventas</h2>
                        <form action="{{ route('pedido.filtrar') }}" method="GET" class="space-y-4">
                            <div>
                                <label for="fecha" class="block font-semibold mb-1">Fecha del pedido:</label>
                                <input type="date" id="fecha" name="fecha"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Selecciona una fecha"
                                    value="{{ request('fecha') ?? '' }}">
                            </div>

                            <div>
                                <label for="estatus" class="block font-semibold mb-1">Estatus del pedido:</label>
                                <select id="estatus" name="estatus"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('estatus') == '1' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="0" {{ request('estatus') == '0' ? 'selected' : '' }}>Entregado</option>
                                    <option value="2" {{ request('estatus') == '2' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                    Aplicar
                                </button>
                                <a href="{{ route('pedido.mostrar') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    Quitar filtros
                                </a>
                                <button type="button" data-modal-cancel="modal-filtros" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="tabla-pedidos" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Folio</th>
                            <th class="text-left py-2">Cliente</th>
                            <th class="text-left py-2">Empleado</th>
                            <th class="text-left py-2">Fecha y hora</th>
                            <th class="text-left py-2">Medio del pedido</th>
                            <th class="text-left py-2">Total</th>
                            <th class="text-left py-2">Método de pago</th>
                            <th class="text-left py-2">Número de transacción</th>
                            <th class="text-left py-2">Notas de remisión</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-left py-2">Ticket</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosPedido as $dato )
                            <tr class="border-b cursor-pointer" title="CLIC PARA VER DETALLES" data-detalles='@json($dato->detalle_pedido)'>
                                <td class="py-2">{{ $dato->pedido_pk }}</td>
                                <td class="py-2">{{ $dato->cliente->nombre_cliente ?? 'Cliente genérico' }}</td>
                                <td class="py-2">{{ $dato->empleado->usuario->usuario }}</td>
                                <td class="py-2">{{ $dato->fecha_hora_pedido }}</td>
                                <td class="py-2">{{ $dato->medio_pedido->nombre_medio_pedido }}</td>
                                <td class="py-2">${{ $dato->monto_total }}</td>
                                <td class="py-2">{{ $dato->tipo_pago->nombre_tipo_pago }}</td>
                                @if ( $dato->numero_transaccion )
                                    <td class="py-2">{{ $dato->numero_transaccion }}</td>
                                @else
                                    <td class="py-2"><em>Sin número de transacción</em></td>
                                @endif
                                @if ( $dato->notas_remision )
                                    <td class="py-2">{{ $dato->notas_remision }}</td>
                                @else
                                    <td class="py-2"><em>Sin notas de remisión</em></td>
                                @endif
                                @if ( $dato->estatus_pedido == 1 )
                                    <td class="py-2">Pendiente</td>
                                @elseif ( $dato->estatus_pedido == 0 )
                                    <td class="py-2">Entregado</td>
                                @elseif ( $dato->estatus_pedido == 2 )
                                    <td class="py-2">Cancelado</td>
                                @else
                                    <td class="py-2"><em>Sin estatus aplicado</em></td>
                                @endif
                                <td class="text-right py-2">
                                    <button onclick="abrirTicket({{ $dato->pedido_pk }})" class="bg-gray-500 text-white px-2 py-1 rounded fix">Ticket</button>
                                </td>
                                <td class="text-right py-2">
                                    @if (  session('rol_pk') == 1)
                                        <a href="{{ route('pedido.datosParaEdicion', $dato->pedido_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded fix">Editar</a>
                                    @endif

                                    @if ( $dato->estatus_pedido == 1 )
                                        <a href="{{ route('pedido.entregado', $dato->pedido_pk) }}" onclick="confirmarEntrega(event)" class="bg-green-500 text-white px-2 py-1 rounded fix">Entregado</a>
                                    @endif

                                    @if ( $dato->estatus_pedido != 2 )
                                        <a href="{{ route('pedido.cancelado', $dato->pedido_pk) }}" onclick="confirmarCancelacion(event)" class="bg-red-500 text-white px-2 py-1 rounded fix">Cancelar</a>
                                    @elseif ( $dato->estatus_pedido == 2 )
                                        <a href="{{ route('pedido.pendiente', $dato->pedido_pk) }}" onclick="confirmarDesCancelacion(event)" class="bg-black text-white px-2 py-1 rounded fix">Deshacer cancelación</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h1 class="text-2xl font-bold mb-4">Métodos de Pago</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-tipo-pago" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Estatus</th>
                            @if ( session('rol_pk') == 1 )
                                <th class="text-right py-2">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosTipo_pago as $dato )
                            <tr class="border-b cursor-pointer">
                                <td class="py-2">{{ $dato->nombre_tipo_pago }}</td>
                                @if ( $dato->estatus_tipo_pago == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                @if ( session('rol_pk') == 1 )
                                    <td class="text-right py-2">
                                        <a href="{{ route('tipo_pago.datosParaEdicion', $dato->tipo_pago_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>

                                        @if ($dato->estatus_tipo_pago == 1)
                                            <a href="{{ route('tipo_pago.baja', $dato->tipo_pago_pk) }}" onclick="confirmarBajaTipoPago(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                        @else
                                            <a href="{{ route('tipo_pago.alta', $dato->tipo_pago_pk) }}" onclick="confirmarAltaTipoPago(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open="modal-tipo-pago" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo tipo de pago</button>
            </div>

            <h1 class="text-2xl font-bold mb-4">Medios de Pedido</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-medio-pedido" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Estatus</th>
                            @if ( session('rol_pk') == 1 )
                                <th class="text-right py-2">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosMedio_pedido as $dato )
                            <tr class="border-b cursor-pointer">
                                <td class="py-2">{{ $dato->nombre_medio_pedido }}</td>
                                @if ( $dato->estatus_medio_pedido == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                @if ( session('rol_pk') == 1 )
                                    <td class="text-right py-2">
                                        <a href="{{ route('medio_pedido.datosParaEdicion', $dato->medio_pedido_pk) }}" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</a>

                                        @if ($dato->estatus_medio_pedido == 1)
                                            <a href="{{ route('medio_pedido.baja', $dato->medio_pedido_pk) }}" onclick="confirmarBajaMedioPedido(event)" class="bg-red-500 text-white px-2 py-1 rounded">Dar de baja</a>
                                        @else
                                            <a href="{{ route('medio_pedido.alta', $dato->medio_pedido_pk) }}" onclick="confirmarAltaMedioPedido(event)" class="bg-green-500 text-white px-2 py-1 rounded">Dar de alta</a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open="modal-medio-pedido" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo medio de pedido</button>
            </div>
        </div>

        <!-- Modal de registro de medio de pedido -->
        <div data-modal="modal-medio-pedido" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Medio de Pedido</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-medped" action="{{ route('medio_pedido.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre_medio_pedido" class="block text-sm font-medium text-gray-700">Nombre
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre_medio_pedido" name="nombre_medio_pedido" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel="modal-medio-pedido" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
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

        <!-- Modal de registro de método de pago -->
        <div data-modal="modal-tipo-pago" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Método de Pago</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-tipopago" action="{{ route('tipo_pago.insertar') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="nombre_tipo_pago" class="block text-sm font-medium text-gray-700">Nombre
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre_tipo_pago" name="nombre_tipo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel="modal-tipo-pago" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
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
            $(document).ready(function () {
                const table = $('#tabla-pedidos').DataTable({
                    language: {
                        search: "Buscar:",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        zeroRecords: "Sin registros de pedidos",
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
                $('#tabla-pedidos tbody').on('click', 'tr', function () {
                    const row = table.row(this);
                    const detalles = $(this).data('detalles'); // Acceder a los detalles desde el atributo data-detalles

                    if (row.child.isShown()) {
                        // Si está expandido, lo oculta
                        row.child.hide();
                        $(this).removeClass('shown');
                    } else {
                        // Si no está expandido, lo muestra
                        row.child(formatDetails(detalles)).show();
                        $(this).addClass('shown');
                    }
                });

                // Función para formatear el contenido de los detalles
                function formatDetails(detalles) {
                    let contenido = 'No hay productos asociados.';
                    
                    if (detalles && detalles.length > 0) {
                        contenido = '<ul>' + detalles.map(detalle => {
                            const producto = detalle.producto ? detalle.producto.nombre_producto : 'Producto no disponible';
                            const tipoProducto = detalle.producto && detalle.producto.tipo_producto ? detalle.producto.tipo_producto.nombre_tipo_producto : 'Tipo no disponible';
                            const precio = detalle.producto ? detalle.producto.precio_producto : 0;
                            const subtotal = detalle.cantidad_producto * precio;
                            
                            return `<li>
                                ${producto} (${tipoProducto}) - ${detalle.cantidad_producto} unidades - Precio: $${precio}
                                ${detalle.cantidad_producto >= 2 ? ` - Subtotal: $${subtotal}` : ''}
                            </li>`;
                        }).join('') + '</ul>';
                    }

                    return `<div class="p-4 bg-gray-50">
                                <strong>Productos:</strong> ${contenido}
                            </div>`;
                }
            });

            // Alerta de confirmación de entrega
            function confirmarEntrega(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas marcar la venta como entregada?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, entregado',
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
                        text: '¿Deseas marcar la venta como cancelada?',
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

            // Alerta de confirmación de baja de método de pago
            function confirmarBajaTipoPago(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de baja este método de pago?',
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

            // Alerta de confirmación de alta de método de pago
            function confirmarAltaTipoPago(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de alta este método de pago?',
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

            // Alerta de confirmación de baja de medio de pedido
            function confirmarBajaMedioPedido(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de baja este medio de pedido?',
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

            // Alerta de confirmación de alta de medio de pedido
            function confirmarAltaMedioPedido(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de alta este medio de pedido?',
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

            function abrirTicket(pedido_pk) {
                const ventana = window.open(`/ticket/${pedido_pk}`, '_blank', 'width=800,height=700');
            }
        </script>
    </div>
</body>
</html>